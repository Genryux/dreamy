<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Services\PaymentPlanService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class InvoicePaymentController extends Controller
{
    protected $paymentPlanService;

    public function __construct(PaymentPlanService $paymentPlanService)
    {
        $this->paymentPlanService = $paymentPlanService;
    }

    public function store(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'method' => 'nullable|string|max:50',
            'type' => 'nullable|string|max:50',
            'reference_no' => 'nullable|string|max:100',
            'payment_schedule_id' => 'nullable|integer|exists:payment_schedules,id',
        ]);

        // Additional validation for one-time payments
        if ($invoice->payment_mode === 'full') {
            // For one-time payments, if this is the first payment, it must be the full amount
            if ($invoice->paid_amount == 0 && $validated['amount'] < $invoice->total_amount) {
                return redirect()->back()->with('error', 'For one-time payments, the first payment must be the full amount of ₱' . number_format($invoice->total_amount, 2));
            }
            
            // For subsequent payments, ensure we don't exceed the remaining balance
            if ($validated['amount'] > $invoice->balance) {
                return redirect()->back()->with('error', 'Payment amount cannot exceed the remaining balance of ₱' . number_format($invoice->balance, 2));
            }
        }

        try {
            // If paying toward a specific schedule, enforce exact remaining due for months 1..9 only
            if (isset($validated['payment_schedule_id'])) {
                $schedule = $invoice->paymentSchedules()->findOrFail($validated['payment_schedule_id']);
                // Only enforce exact amount for monthly installments (installment_number > 0)
                if ($schedule->installment_number > 0) {
                    $remainingDue = round($schedule->amount_due - $schedule->amount_paid, 2);
                    $amount = round($validated['amount'], 2);
                    if ($amount !== $remainingDue) {
                        return redirect()->back()->with('error', 'Payment must match the exact remaining amount for the selected schedule (₱' . number_format($remainingDue, 2) . ').');
                    }
                }
            }

            $paymentData = [
                'payment_date' => $validated['payment_date'],
                'method' => $validated['method'] ?? null,
                'type' => $validated['type'] ?? null,
                'reference_no' => $validated['reference_no'] ?? null,
            ];

            // If a specific schedule is selected, use the new method
            if (isset($validated['payment_schedule_id'])) {
                $this->paymentPlanService->recordPaymentToSchedule(
                    $invoice, 
                    $validated['payment_schedule_id'], 
                    $validated['amount'], 
                    $paymentData
                );
            } else {
                // Fallback to original method for invoices without payment plans
                $this->paymentPlanService->recordPayment($invoice, $validated['amount'], $paymentData);
            }

            return redirect()->back()->with('success', 'Payment recorded successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function getPayments(Request $request)
    {
        $query = InvoicePayment::with(['invoice.student']);

        if ($search = $request->input('search.value')) {
            $query->whereAny(['reference_no', 'method', 'type'], 'like', "%{$search}%")
                ->orWhereHas('invoice', function ($q) use ($search) {
                    $q->whereHas('student', function ($qq) use ($search) {
                        $qq->where('last_name', 'like', "%{$search}%")
                           ->orWhere('first_name', 'like', "%{$search}%");
                    });
                });
        }

        $total = $query->count();
        $filtered = $total;

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);

        $data = $query
            ->offset($start)
            ->limit($length)
            ->orderBy('payment_date', 'desc')
            ->get()
            ->map(function ($payment, $idx) use ($start) {
                return [
                    'index' => $start + $idx + 1,
                    'date' => $payment->payment_date
                        ? \Illuminate\Support\Carbon::parse($payment->payment_date)->format('Y-m-d')
                        : '-',
                    'reference_no' => $payment->reference_no,
                    'method' => $payment->method ?? '-',
                    'type' => $payment->type ?? '-',
                    'amount' => '₱ ' . number_format($payment->amount, 2),
                    'student' => optional($payment->invoice->student)->full_name ?? '-',
                    'invoice_number' => optional($payment->invoice)->invoice_number ?? '-',
                    'invoice_id' => $payment->invoice_id,
                ];
            });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }
}
