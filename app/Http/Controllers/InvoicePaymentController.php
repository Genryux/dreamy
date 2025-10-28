<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Services\PaymentPlanService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

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
            'remaining_balance' => 'nullable|numeric|min:0', // Remaining balance for installment calculation
            'payment_date' => 'required|date',
            'method' => 'nullable|string|max:50',
            'type' => 'nullable|string|max:50',
            'reference_no' => 'nullable|string|max:100',
            'payment_schedule_id' => 'nullable|integer|exists:payment_schedules,id',
            'custom_discount_enabled' => 'nullable|boolean',
            'selected_discounts' => 'nullable|array',
            'selected_discounts.*' => 'exists:discounts,id',
            'pin' => 'required|string|size:6',
        ]);

        // Verify PIN for payment authorization
        $user = auth()->user();
        if (!$user->pin || !$user->pin_enabled) {
            return response()->json([
                'success' => false,
                'message' => 'PIN security is not enabled for your account.'
            ], 403);
        }

        // Debug logging (remove in production)
        \Log::info('Payment PIN verification attempt', [
            'user_id' => $user->id,
            'pin_enabled' => $user->pin_enabled,
            'pin_length' => strlen($user->pin),
            'input_pin_length' => strlen($validated['pin']),
            'timestamp' => now()
        ]);

        if (!Hash::check($validated['pin'], $user->pin)) {
            // Log failed PIN attempt for security
            \Log::warning('Failed PIN verification attempt for payment recording', [
                'user_id' => $user->id,
                'invoice_id' => $invoice->id,
                'amount' => $validated['amount'],
                'ip' => $request->ip(),
                'timestamp' => now()
            ]);

            // Check for rate limiting (max 5 attempts per minute)
            $cacheKey = 'payment_pin_attempts_' . $user->id;
            $attempts = \Cache::get($cacheKey, 0);
            
            if ($attempts >= 5) {
                \Log::critical('Payment PIN verification rate limit exceeded', [
                    'user_id' => $user->id,
                    'ip' => $request->ip(),
                    'timestamp' => now()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Too many failed PIN attempts. Please try again in a few minutes.'
                ], 429);
            }
            
            // Increment attempt counter (expires in 1 minute)
            \Cache::put($cacheKey, $attempts + 1, 60);

            return response()->json([
                'success' => false,
                'message' => 'Invalid PIN. Please try again.'
            ], 401);
        }

        // Clear any failed attempts on successful verification
        $cacheKey = 'payment_pin_attempts_' . $user->id;
        \Cache::forget($cacheKey);

        // Use the original amount as the payment amount
        $originalAmount = $validated['amount']; // Original amount entered by user
        $finalAmount = $originalAmount; // Use original amount for payment
        $remainingBalance = $validated['remaining_balance'] ?? $originalAmount; // Remaining balance for installment calculation
        
        // Calculate discount breakdown for record keeping
        $earlyDiscount = 0;
        if ($invoice->student && $invoice->student->enrollmentPeriod) {
            $enrollmentPeriod = $invoice->student->enrollmentPeriod;
            if ($enrollmentPeriod->isEarlyEnrollment()) {
                $earlyDiscount = $enrollmentPeriod->calculateEarlyDiscount($invoice->total_amount);
            }
        }
        
        // Calculate custom discounts for record keeping (applied to total invoice amount)
        $customDiscountsTotal = 0;
        if (isset($validated['custom_discount_enabled']) && $validated['custom_discount_enabled'] && isset($validated['selected_discounts'])) {
            foreach ($validated['selected_discounts'] as $discountId) {
                $discount = \App\Models\Discount::find($discountId);
                if ($discount && $discount->is_active) {
                    // Apply custom discount to total invoice amount, not payment amount
                    $customDiscountsTotal += $discount->calculateDiscount($invoice->total_amount);
                }
            }
        }

        // Log successful PIN verification for audit
        \Log::info('Payment recording authorized with PIN verification', [
            'user_id' => $user->id,
            'invoice_id' => $invoice->id,
            'original_amount' => $originalAmount,
            'final_amount' => $finalAmount,
            'early_discount' => $earlyDiscount,
            'custom_discounts' => $customDiscountsTotal,
            'total_discount' => $earlyDiscount + $customDiscountsTotal,
            'remaining_balance' => $remainingBalance,
            'student_id' => $invoice->student_id,
            'timestamp' => now()
        ]);

        // Additional validation for one-time payments
        if ($invoice->payment_mode === 'full') {
            // For one-time payments, if this is the first payment, it must be the full amount
            if ($invoice->paid_amount == 0 && $validated['amount'] < $invoice->total_amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'For one-time payments, the first payment must be the full amount of ₱' . number_format($invoice->total_amount, 2)
                ], 400);
            }
            
            // For subsequent payments, ensure we don't exceed the remaining balance
            if ($validated['amount'] > $invoice->balance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment amount cannot exceed the remaining balance of ₱' . number_format($invoice->balance, 2)
                ], 400);
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
                        return response()->json([
                            'success' => false,
                            'message' => 'Payment must match the exact remaining amount for the selected schedule (₱' . number_format($remainingDue, 2) . ').'
                        ], 400);
                    }
                }
            }

                $paymentData = [
                    'payment_date' => $validated['payment_date'],
                    'method' => $validated['method'] ?? null,
                    'type' => $validated['type'] ?? null,
                    'reference_no' => $validated['reference_no'] ?? null,
                    'original_amount' => $originalAmount,
                    'early_discount' => $earlyDiscount,
                    'custom_discounts' => $customDiscountsTotal,
                    'total_discount' => $earlyDiscount + $customDiscountsTotal,
                    'remaining_balance' => $remainingBalance,
                ];

            // If a specific schedule is selected, use the new method
            if (isset($validated['payment_schedule_id'])) {
                $this->paymentPlanService->recordPaymentToSchedule(
                    $invoice, 
                    $validated['payment_schedule_id'], 
                    $finalAmount, // Use final amount after discounts
                    $paymentData
                );
            } else {
                // Fallback to original method for invoices without payment plans
                $this->paymentPlanService->recordPayment($invoice, $finalAmount, $paymentData); // Use final amount after discounts
            }

            // Log the activity
            activity('financial_management')
                ->causedBy(auth()->user())
                ->performedOn($invoice)
                ->withProperties([
                    'action' => 'recorded_payment',
                    'invoice_id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'student_id' => $invoice->student_id,
                    'student_name' => $invoice->student->user->first_name . ' ' . $invoice->student->user->last_name,
                    'payment_amount' => $finalAmount,
                    'original_amount' => $originalAmount,
                    'early_discount' => $earlyDiscount,
                    'custom_discounts' => $customDiscountsTotal,
                    'total_discount' => $earlyDiscount + $customDiscountsTotal,
                    'payment_date' => $validated['payment_date'],
                    'payment_method' => $validated['method'] ?? null,
                    'payment_type' => $validated['type'] ?? null,
                    'reference_no' => $validated['reference_no'] ?? null,
                    'payment_schedule_id' => $validated['payment_schedule_id'] ?? null,
                    'remaining_balance' => $remainingBalance,
                    'new_invoice_balance' => $invoice->fresh()->balance,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ])
                ->log('Payment recorded');

            return response()->json([
                'success' => true,
                'message' => 'Payment recorded successfully.'
            ]);
        } catch (\Throwable $th) {
            \Log::error('Payment recording failed', [
                'user_id' => auth()->id(),
                'invoice_id' => $invoice->id,
                'error' => $th->getMessage(),
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function getPayments(Request $request)
    {
        $query = InvoicePayment::with(['invoice.student.user']);

        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_no', 'like', "%{$search}%")
                  ->orWhere('method', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhereHas('invoice', function ($qq) use ($search) {
                      $qq->whereHas('student', function ($qqq) use ($search) {
                          $qqq->whereHas('user', function ($qqqq) use ($search) {
                              $qqqq->where('last_name', 'like', "%{$search}%")
                                   ->orWhere('first_name', 'like', "%{$search}%");
                          });
                      });
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
