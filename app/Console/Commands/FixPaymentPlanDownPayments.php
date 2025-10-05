<?php

namespace App\Console\Commands;

use App\Models\PaymentPlan;
use App\Models\InvoicePayment;
use Illuminate\Console\Command;

class FixPaymentPlanDownPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment-plan:fix-down-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add missing down payment records to existing payment plans';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Finding payment plans with missing down payment records...');
        
        $fixed = 0;
        $skipped = 0;
        
        PaymentPlan::with(['schedules', 'invoice'])->chunk(100, function ($plans) use (&$fixed, &$skipped) {
            foreach ($plans as $plan) {
                if ($plan->down_payment_amount <= 0) {
                    $skipped++;
                    continue;
                }
                
                // Find down payment schedule
                $downPaymentSchedule = $plan->schedules()
                    ->where('installment_number', 0)
                    ->first();
                
                if (!$downPaymentSchedule) {
                    $this->warn("Plan {$plan->id}: No down payment schedule found");
                    $skipped++;
                    continue;
                }
                
                // Check if payment record exists
                $existingPayment = InvoicePayment::where('payment_schedule_id', $downPaymentSchedule->id)
                    ->first();
                
                if ($existingPayment) {
                    $this->line("Plan {$plan->id}: Down payment already recorded (₱" . number_format($plan->down_payment_amount, 2) . ")");
                    $skipped++;
                    continue;
                }
                
                // Create missing payment record
                $activeTerm = \App\Models\AcademicTerms::where('is_active', true)->first();
                
                InvoicePayment::create([
                    'invoice_id' => $plan->invoice_id,
                    'payment_schedule_id' => $downPaymentSchedule->id,
                    'academic_term_id' => $activeTerm ? $activeTerm->id : $plan->invoice->academic_term_id,
                    'amount' => $plan->down_payment_amount,
                    'payment_date' => $downPaymentSchedule->due_date ?? now(),
                    'method' => 'Cash',
                    'type' => 'Down Payment',
                    'reference_no' => null, // Will be auto-generated
                ]);
                
                // Update invoice status
                $invoice = $plan->invoice;
                $invoice->refresh();
                
                $oldStatus = $invoice->status;
                
                if ($invoice->balance <= 0) {
                    $invoice->status = 'paid';
                } elseif ($invoice->paid_amount > 0) {
                    $invoice->status = 'partially_paid';
                }
                $invoice->save();
                
                $fixed++;
                $this->info("✓ Plan {$plan->id}: Fixed! Added down payment of ₱" . number_format($plan->down_payment_amount, 2) . " (Invoice #{$plan->invoice->invoice_number} status: {$oldStatus} → {$invoice->status})");
            }
        });
        
        $this->newLine();
        $this->info("========================================");
        $this->info("✅ Summary:");
        $this->info("   Fixed: {$fixed} payment plan(s)");
        $this->info("   Skipped: {$skipped} payment plan(s)");
        $this->info("========================================");
        
        return 0;
    }
}

