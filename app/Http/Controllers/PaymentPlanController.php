<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\PaymentPlan;
use App\Services\PaymentPlanService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentPlanController extends Controller
{
    protected $paymentPlanService;

    public function __construct(PaymentPlanService $paymentPlanService)
    {
        $this->paymentPlanService = $paymentPlanService;
    }

    /**
     * Create a payment plan for an invoice.
     */
    public function store(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'down_payment' => 'required|numeric|min:0',
            'installment_months' => 'nullable|integer|min:1|max:12',
            'start_date' => 'nullable|date',
        ]);

        try {
            $installmentMonths = $validated['installment_months'] ?? 9;
            $startDate = isset($validated['start_date']) 
                ? Carbon::parse($validated['start_date']) 
                : null;

            $paymentPlan = $this->paymentPlanService->createInstallmentPlan(
                $invoice,
                $validated['down_payment'],
                $installmentMonths,
                $startDate
            );

            return redirect()->back()->with('success', 'Payment plan created successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Get payment plan details.
     */
    public function show(Invoice $invoice)
    {
        if (!$invoice->has_payment_plan) {
            return response()->json(['error' => 'Invoice has no payment plan'], 404);
        }

        $summary = $this->paymentPlanService->getPaymentPlanSummary($invoice);
        
        return response()->json($summary);
    }

    /**
     * Calculate payment plan preview (without saving).
     */
    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'total_amount' => 'required|numeric|min:0',
            'down_payment' => 'required|numeric|min:0',
            'installment_months' => 'nullable|integer|min:1|max:12',
        ]);

        $installmentMonths = $validated['installment_months'] ?? 9;
        
        $calculation = PaymentPlan::calculate(
            $validated['total_amount'],
            $validated['down_payment'],
            $installmentMonths
        );

        // Generate preview schedule
        $startDate = Carbon::now()->addMonth()->startOfMonth();
        $schedule = [
            [
                'installment_number' => 0,
                'description' => 'Down Payment',
                'amount' => $calculation['down_payment_amount'],
                'due_date' => Carbon::now()->format('Y-m-d'),
            ]
        ];

        for ($i = 1; $i <= $installmentMonths; $i++) {
            $amount = ($i === 1) ? $calculation['first_month_amount'] : $calculation['monthly_amount'];
            $dueDate = $startDate->copy()->addMonths($i - 1);
            
            $schedule[] = [
                'installment_number' => $i,
                'description' => "Month {$i}",
                'amount' => $amount,
                'due_date' => $dueDate->format('Y-m-d'),
            ];
        }

        return response()->json([
            'plan' => $calculation,
            'schedule' => $schedule,
        ]);
    }

    /**
     * Update payment plan (adjust down payment).
     */
    public function update(Request $request, PaymentPlan $paymentPlan)
    {
        $validated = $request->validate([
            'down_payment' => 'required|numeric|min:0',
        ]);

        try {
            $updatedPlan = $this->paymentPlanService->adjustDownPayment(
                $paymentPlan,
                $validated['down_payment']
            );

            return redirect()->back()->with('success', 'Payment plan updated successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Delete payment plan.
     */
    public function destroy(PaymentPlan $paymentPlan)
    {
        try {
            $invoice = $paymentPlan->invoice;
            
            $paymentPlan->delete();
            
            $invoice->update([
                'has_payment_plan' => false,
                'payment_mode' => 'flexible',
            ]);

            return redirect()->back()->with('success', 'Payment plan removed successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}

