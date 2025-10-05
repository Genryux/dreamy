<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\PaymentPlan;
use App\Models\PaymentSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentPlanService
{
    /**
     * Create a payment plan for an invoice with installments.
     * 
     * @param Invoice $invoice
     * @param float $downPaymentAmount
     * @param int $installmentMonths
     * @param Carbon|null $startDate
     * @return PaymentPlan
     */
    public function createInstallmentPlan(Invoice $invoice, $downPaymentAmount, $installmentMonths = 9, $startDate = null)
    {
        return DB::transaction(function () use ($invoice, $downPaymentAmount, $installmentMonths, $startDate) {
            $totalAmount = $invoice->total_amount;
            
            // Calculate payment plan
            $planData = PaymentPlan::calculate($totalAmount, $downPaymentAmount, $installmentMonths);
            $planData['payment_type'] = 'installment';
            $planData['invoice_id'] = $invoice->id;
            
            // Create payment plan
            $paymentPlan = PaymentPlan::create($planData);
            
            // Set start date (default to next month)
            $startDate = $startDate ?? Carbon::now()->addMonth()->startOfMonth();
            
            // Get active academic term
            $activeTerm = \App\Models\AcademicTerms::where('is_active', true)->first();
            
            if (!$activeTerm) {
                throw new \Exception('No active academic term found.');
            }
            
            // Create down payment schedule (installment_number = 0)
            $downPaymentSchedule = PaymentSchedule::create([
                'payment_plan_id' => $paymentPlan->id,
                'invoice_id' => $invoice->id,
                'installment_number' => 0,
                'amount_due' => $downPaymentAmount,
                'amount_paid' => 0, // Student hasn't paid yet
                'due_date' => Carbon::now(),
                'status' => 'pending', // Wait for actual payment
                'description' => 'Down Payment',
            ]);
            
            // Don't create payment record yet - wait for actual payment
            
            // Create monthly payment schedules
            for ($i = 1; $i <= $installmentMonths; $i++) {
                $amount = ($i === 1) ? $planData['first_month_amount'] : $planData['monthly_amount'];
                $dueDate = $startDate->copy()->addMonths($i - 1);
                
                PaymentSchedule::create([
                    'payment_plan_id' => $paymentPlan->id,
                    'invoice_id' => $invoice->id,
                    'installment_number' => $i,
                    'amount_due' => $amount,
                    'amount_paid' => 0,
                    'due_date' => $dueDate,
                    'status' => 'pending',
                    'description' => $dueDate->format('F Y'), // e.g., "November 2025"
                ]);
            }
            
            // Update invoice
            $invoice->update([
                'has_payment_plan' => true,
                'payment_mode' => 'installment',
            ]);
            
            // Invoice status remains 'unpaid' until actual payments are made
            
            return $paymentPlan;
        });
    }

    /**
     * Record a payment against a payment schedule.
     * 
     * @param Invoice $invoice
     * @param float $amount
     * @param array $paymentData
     * @return \App\Models\InvoicePayment
     */
    public function recordPayment(Invoice $invoice, $amount, array $paymentData = [])
    {
        return DB::transaction(function () use ($invoice, $amount, $paymentData) {
            $activeTerm = \App\Models\AcademicTerms::where('is_active', true)->first();
            
            if (!$activeTerm) {
                throw new \Exception('No active academic term found.');
            }

            // If invoice has payment plan, apply to schedules
            if ($invoice->has_payment_plan) {
                $remainingAmount = $amount;
                
                // Get unpaid/partial schedules in order
                $schedules = $invoice->paymentSchedules()
                    ->whereIn('status', ['pending', 'partial', 'overdue'])
                    ->orderBy('installment_number')
                    ->get();
                
                foreach ($schedules as $schedule) {
                    if ($remainingAmount <= 0) break;
                    
                    $scheduleBalance = $schedule->amount_due - $schedule->amount_paid;
                    $paymentAmount = min($remainingAmount, $scheduleBalance);
                    
                    // Create payment record
                    $payment = \App\Models\InvoicePayment::create([
                        'invoice_id' => $invoice->id,
                        'payment_schedule_id' => $schedule->id,
                        'academic_term_id' => $activeTerm->id,
                        'amount' => $paymentAmount,
                        'payment_date' => $paymentData['payment_date'] ?? now(),
                        'method' => $paymentData['method'] ?? null,
                        'type' => $paymentData['type'] ?? 'Installment Payment',
                        'reference_no' => $paymentData['reference_no'] ?? null,
                    ]);
                    
                    // Update schedule
                    $schedule->amount_paid += $paymentAmount;
                    $schedule->updateStatus();
                    
                    $remainingAmount -= $paymentAmount;
                }
                
                // Check if this payment affects the down payment schedule
                $downPaymentSchedule = $invoice->paymentSchedules()
                    ->where('installment_number', 0)
                    ->first();
                
                if ($downPaymentSchedule && $downPaymentSchedule->amount_paid > 0) {
                    // Down payment has been made, recalculate the plan
                    $this->recalculatePaymentPlan($invoice);
                }
                
                // Update invoice status
                $invoice->refresh();
                if ($invoice->balance <= 0) {
                    $invoice->status = 'paid';
                } elseif ($invoice->paid_amount > 0) {
                    $invoice->status = 'partially_paid';
                }
                $invoice->save();
                
                return $payment ?? null;
                
            } else {
                // Flexible payment (existing logic)
                $payment = \App\Models\InvoicePayment::create([
                    'invoice_id' => $invoice->id,
                    'academic_term_id' => $activeTerm->id,
                    'amount' => $amount,
                    'payment_date' => $paymentData['payment_date'] ?? now(),
                    'method' => $paymentData['method'] ?? null,
                    'type' => $paymentData['type'] ?? null,
                    'reference_no' => $paymentData['reference_no'] ?? null,
                ]);
                
                $invoice->refresh();
                if ($invoice->balance <= 0) {
                    $invoice->status = 'paid';
                    $invoice->save();
                }
                
                return $payment;
            }
        });
    }

    /**
     * Update payment plan with actual down payment.
     * Useful when down payment differs from expected.
     * 
     * @param PaymentPlan $paymentPlan
     * @param float $actualDownPayment
     * @return PaymentPlan
     */
    public function adjustDownPayment(PaymentPlan $paymentPlan, $actualDownPayment)
    {
        return DB::transaction(function () use ($paymentPlan, $actualDownPayment) {
            // Recalculate plan
            $planData = PaymentPlan::calculate(
                $paymentPlan->total_amount,
                $actualDownPayment,
                $paymentPlan->installment_months
            );
            
            // Update payment plan
            $paymentPlan->update($planData);
            
            // Update down payment schedule
            $downPaymentSchedule = $paymentPlan->schedules()
                ->where('installment_number', 0)
                ->first();
            
            if ($downPaymentSchedule) {
                $downPaymentSchedule->update([
                    'amount_due' => $actualDownPayment,
                    'amount_paid' => $actualDownPayment,
                ]);
            }
            
            // Update monthly schedules
            $monthlySchedules = $paymentPlan->schedules()
                ->where('installment_number', '>', 0)
                ->orderBy('installment_number')
                ->get();
            
            foreach ($monthlySchedules as $index => $schedule) {
                $amount = ($schedule->installment_number === 1) 
                    ? $planData['first_month_amount'] 
                    : $planData['monthly_amount'];
                
                $schedule->update(['amount_due' => $amount]);
            }
            
            return $paymentPlan->fresh();
        });
    }

    /**
     * Get payment plan summary for an invoice.
     * 
     * @param Invoice $invoice
     * @return array|null
     */
    public function getPaymentPlanSummary(Invoice $invoice)
    {
        if (!$invoice->has_payment_plan) {
            return null;
        }

        $paymentPlan = $invoice->paymentPlan;
        $schedules = $invoice->paymentSchedules()->orderBy('installment_number')->get();
        
        return [
            'total_amount' => $paymentPlan->total_amount,
            'down_payment' => $paymentPlan->down_payment_amount,
            'remaining_amount' => $paymentPlan->remaining_amount,
            'monthly_amount' => $paymentPlan->monthly_amount,
            'first_month_amount' => $paymentPlan->first_month_amount,
            'installment_months' => $paymentPlan->installment_months,
            'schedules' => $schedules,
            'paid_schedules' => $schedules->where('status', 'paid')->count(),
            'pending_schedules' => $schedules->whereIn('status', ['pending', 'overdue'])->count(),
            'next_due' => $invoice->next_due_schedule,
        ];
    }

    /**
     * Recalculate payment plan based on actual student down payment.
     * 
     * @param Invoice $invoice
     * @return void
     */
    public function recalculatePaymentPlan(Invoice $invoice)
    {
        if (!$invoice->has_payment_plan) {
            return;
        }

        $paymentPlan = $invoice->paymentPlan;
        if (!$paymentPlan) {
            return;
        }

        // Get the actual down payment amount paid by student
        $downPaymentSchedule = $invoice->paymentSchedules()
            ->where('installment_number', 0)
            ->first();

        if (!$downPaymentSchedule || $downPaymentSchedule->amount_paid <= 0) {
            return;
        }

        $actualDownPayment = $downPaymentSchedule->amount_paid;
        $totalAmount = $paymentPlan->total_amount;
        $installmentMonths = $paymentPlan->installment_months;

        // Calculate remaining balance after student's actual payment
        $remainingBalance = $totalAmount - $actualDownPayment;
        
        // Calculate monthly amount (shortfall is already included in remainingBalance)
        $monthlyAmount = round($remainingBalance / $installmentMonths, 2);
        
        // Distribute rounding residual to the first month so totals align exactly
        $totalMonthly = $monthlyAmount * $installmentMonths;
        $difference = round($remainingBalance - $totalMonthly, 2); // can be negative/positive within cents
        $firstMonthAmount = round($monthlyAmount + $difference, 2);

        // Update payment plan
        $paymentPlan->update([
            'remaining_amount' => $remainingBalance,
            'monthly_amount' => $monthlyAmount,
            'first_month_amount' => $firstMonthAmount,
        ]);

        // Update down payment schedule to reflect actual payment
        // Since the shortfall is moved to first month, the down payment should be considered fully paid
        $downPaymentSchedule->update([
            'amount_due' => $actualDownPayment,
            'status' => 'paid',
        ]);

        // Update monthly schedules (skip down payment schedule)
        $monthlySchedules = $invoice->paymentSchedules()
            ->where('installment_number', '>', 0)
            ->orderBy('installment_number')
            ->get();

        foreach ($monthlySchedules as $index => $schedule) {
            $newAmount = ($schedule->installment_number === 1) ? $firstMonthAmount : $monthlyAmount;
            
            // Only update if no payment has been made yet
            if ($schedule->amount_paid == 0) {
                $schedule->update([
                    'amount_due' => $newAmount,
                ]);
            }
        }
    }

    /**
     * Record a payment against a specific payment schedule.
     * 
     * @param Invoice $invoice
     * @param int $scheduleId
     * @param float $amount
     * @param array $paymentData
     * @return \App\Models\InvoicePayment
     */
    public function recordPaymentToSchedule(Invoice $invoice, $scheduleId, $amount, array $paymentData = [])
    {
        return DB::transaction(function () use ($invoice, $scheduleId, $amount, $paymentData) {
            $activeTerm = \App\Models\AcademicTerms::where('is_active', true)->first();
            
            if (!$activeTerm) {
                throw new \Exception('No active academic term found.');
            }

            // Find the specific schedule
            $schedule = $invoice->paymentSchedules()->findOrFail($scheduleId);
            
            // Check if schedule is already fully paid
            if ($schedule->status === 'paid') {
                throw new \Exception('This payment schedule is already fully paid.');
            }

            // Check if this is the first payment and it's not the down payment
            if ($invoice->paid_amount == 0 && $schedule->installment_number > 0) {
                throw new \Exception('Down payment must be paid first.');
            }

            // Create payment record
            $payment = \App\Models\InvoicePayment::create([
                'invoice_id' => $invoice->id,
                'payment_schedule_id' => $schedule->id,
                'academic_term_id' => $activeTerm->id,
                'amount' => $amount,
                'payment_date' => $paymentData['payment_date'] ?? now(),
                'method' => $paymentData['method'] ?? null,
                'type' => $paymentData['type'] ?? 'Installment Payment',
                'reference_no' => $paymentData['reference_no'] ?? null,
            ]);
            
            // Update schedule
            $schedule->amount_paid += $amount;
            $schedule->updateStatus();
            
            // Check if this payment affects the down payment schedule and trigger recalculation
            if ($schedule->installment_number === 0 && $schedule->amount_paid > 0) {
                $this->recalculatePaymentPlan($invoice);
            }
            
            // Update invoice status
            $invoice->refresh();
            if ($invoice->balance <= 0) {
                $invoice->status = 'paid';
            } elseif ($invoice->paid_amount > 0) {
                $invoice->status = 'partially_paid';
            }
            $invoice->save();
            
            return $payment;
        });
    }
}

