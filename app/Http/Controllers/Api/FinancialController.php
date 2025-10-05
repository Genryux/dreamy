<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\AcademicTerms;
use App\Models\StudentEnrollment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FinancialController extends Controller
{
    /**
     * Get student's invoices for a specific academic term (or current if not specified)
     */
    public function getCurrentInvoices(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user->student) {
            return response()->json(['error' => 'User is not a student'], 403);
        }

        // Get term ID from request, default to active term
        $termId = $request->input('term_id');
        if ($termId) {
            $selectedTerm = AcademicTerms::find($termId);
            if (!$selectedTerm) {
                return response()->json(['error' => 'Academic term not found'], 404);
            }
        } else {
            $selectedTerm = AcademicTerms::where('is_active', true)->first();
            if (!$selectedTerm) {
                return response()->json(['error' => 'No active academic term found'], 404);
            }
        }

        // Check if student was enrolled in the selected term
        $enrollment = StudentEnrollment::where('student_id', $user->student->id)
            ->where('academic_term_id', $selectedTerm->id)
            ->first();

        if (!$enrollment) {
            // Return empty data instead of 404 for terms student wasn't enrolled in
            return response()->json([
                'success' => true,
                'data' => [
                    'academic_term' => $selectedTerm->getFullNameAttribute(),
                    'academic_term_id' => $selectedTerm->id,
                    'invoices' => [],
                    'summary' => [
                        'total_invoices' => 0,
                        'total_amount' => 0,
                        'total_paid' => 0,
                        'total_balance' => 0,
                    ]
                ]
            ]);
        }

        $invoices = Invoice::with([
            'items.fee',
            'payments',
            'academicTerm',
            'paymentPlan.schedules'
        ])->where('student_id', $user->student->id)
          ->where('academic_term_id', $selectedTerm->id)
          ->orderBy('created_at', 'desc')
          ->get();

        // Format invoice data
        $invoicesData = $invoices->map(function ($invoice) {
            $invoiceData = [
                'id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'status' => $invoice->status,
                'total_amount' => $invoice->total_amount,
                'paid_amount' => $invoice->paid_amount,
                'balance' => $invoice->balance,
                'created_at' => $invoice->created_at->format('M d, Y'),
                // 'due_date' => $invoice->created_at->addDays(30)->format('M d, Y'), // Removed: no due_date field in database
                'items' => $invoice->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'fee_name' => $item->fee->name ?? '-',
                        'amount' => $item->amount,
                    ];
                })->toArray(),
                'payment_status' => $this->getPaymentStatus($invoice),
                'has_payment_plan' => $invoice->has_payment_plan,
                'payment_mode' => $invoice->payment_mode,
            ];

            // Add payment plan details if exists
            if ($invoice->has_payment_plan && $invoice->paymentPlan) {
                $invoiceData['payment_plan'] = [
                    'id' => $invoice->paymentPlan->id,
                    'down_payment_amount' => $invoice->paymentPlan->down_payment_amount,
                    'monthly_amount' => $invoice->paymentPlan->monthly_amount,
                    'first_month_amount' => $invoice->paymentPlan->first_month_amount,
                    'installment_months' => $invoice->paymentPlan->installment_months,
                    'schedules' => $invoice->paymentPlan->schedules->map(function ($schedule) {
                        return [
                            'installment_number' => $schedule->installment_number,
                            'description' => $schedule->description,
                            'amount_due' => $schedule->amount_due,
                            'amount_paid' => $schedule->amount_paid,
                            'due_date' => $schedule->due_date ? $schedule->due_date->format('M d, Y') : null,
                            'status' => $schedule->status,
                        ];
                    })->toArray(),
                ];
            }

            return $invoiceData;
        });

        return response()->json([
            'success' => true,
            'data' => [
                'invoices' => $invoicesData,
                'academic_term' => $selectedTerm->getFullNameAttribute(),
                'academic_term_id' => $selectedTerm->id,
                'summary' => [
                    'total_invoices' => $invoices->count(),
                    'total_amount' => $invoices->sum('total_amount'),
                    'total_paid' => $invoices->sum('paid_amount'),
                    'total_balance' => $invoices->sum('balance'),
                ]
            ]
        ]);
    }

    /**
     * Get student's payment history for a specific academic term (or current if not specified)
     */
    public function getCurrentPaymentHistory(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user->student) {
            return response()->json(['error' => 'User is not a student'], 403);
        }

        // Get term ID from request, default to active term
        $termId = $request->input('term_id');
        if ($termId) {
            $selectedTerm = AcademicTerms::find($termId);
            if (!$selectedTerm) {
                return response()->json(['error' => 'Academic term not found'], 404);
            }
        } else {
            $selectedTerm = AcademicTerms::where('is_active', true)->first();
            if (!$selectedTerm) {
                return response()->json(['error' => 'No active academic term found'], 404);
            }
        }

        // Check if student was enrolled in the selected term
        $enrollment = StudentEnrollment::where('student_id', $user->student->id)
            ->where('academic_term_id', $selectedTerm->id)
            ->first();

        if (!$enrollment) {
            // Return empty data instead of 404 for terms student wasn't enrolled in
            return response()->json([
                'success' => true,
                'data' => [
                    'academic_term' => $selectedTerm->getFullNameAttribute(),
                    'academic_term_id' => $selectedTerm->id,
                    'payments' => [],
                    'summary' => [
                        'total_payments' => 0,
                        'total_amount' => 0,
                        'status' => 'No payments',
                    ]
                ]
            ]);
        }

        // Get payments for selected term
        $payments = InvoicePayment::with([
            'invoice',
            'academicTerm'
        ])->whereHas('invoice', function ($query) use ($user) {
            $query->where('student_id', $user->student->id);
        })->where('academic_term_id', $selectedTerm->id)
          ->orderBy('payment_date', 'desc')
          ->get();

        // Format payment data
        $paymentsData = $payments->map(function ($payment) {
            return [
                'id' => $payment->id,
                'invoice_number' => $payment->invoice->invoice_number ?? '-',
                'amount' => $payment->amount,
                'payment_date' => $payment->payment_date ? 
                    \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') : '-',
                'method' => $payment->method ?: '-',
                'type' => $payment->type ?: '-',
                'reference_no' => $payment->reference_no ?: '-',
                'status' => 'completed', // All payments in history are completed
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'payments' => $paymentsData,
                'academic_term' => $selectedTerm->getFullNameAttribute(),
                'academic_term_id' => $selectedTerm->id,
                'summary' => [
                    'total_payments' => $payments->count(),
                    'total_amount' => $payments->sum('amount'),
                    'last_payment_date' => $payments->first() ? 
                        \Carbon\Carbon::parse($payments->first()->payment_date)->format('M d, Y') : null,
                ]
            ]
        ]);
    }

    /**
     * Get combined financial summary (invoices + payments)
     */
    public function getFinancialSummary(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user->student) {
            return response()->json(['error' => 'User is not a student'], 403);
        }

        $activeTerm = AcademicTerms::where('is_active', true)->first();
        if (!$activeTerm) {
            return response()->json(['error' => 'No active academic term found'], 404);
        }

        $enrollment = StudentEnrollment::where('student_id', $user->student->id)
            ->where('academic_term_id', $activeTerm->id)
            ->first();

        if (!$enrollment) {
            return response()->json(['error' => 'Student not enrolled in current term'], 404);
        }

        // Get invoices
        $invoices = Invoice::with(['items.fee', 'payments'])
            ->where('student_id', $user->student->id)
            ->where('academic_term_id', $activeTerm->id)
            ->get();

        // Get payments
        $payments = InvoicePayment::with(['invoice'])
            ->whereHas('invoice', function ($query) use ($user) {
                $query->where('student_id', $user->student->id);
            })->where('academic_term_id', $activeTerm->id)
            ->get();

        // Format invoices data
        $invoicesData = $invoices->map(function ($invoice) {
            return [
                'id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'status' => $invoice->status,
                'total_amount' => $invoice->total_amount,
                'paid_amount' => $invoice->paid_amount,
                'balance' => $invoice->balance,
                'created_at' => $invoice->created_at->format('M d, Y'),
                // 'due_date' => $invoice->created_at->addDays(30)->format('M d, Y'), // Removed: no due_date field in database
                'items' => $invoice->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'fee_name' => $item->fee->name ?? '-',
                        'amount' => $item->amount,
                    ];
                })->toArray(),
                'payment_status' => $this->getPaymentStatus($invoice),
            ];
        });

        // Format payments data
        $paymentsData = $payments->map(function ($payment) {
            return [
                'id' => $payment->id,
                'invoice_number' => $payment->invoice->invoice_number ?? '-',
                'amount' => $payment->amount,
                'payment_date' => $payment->payment_date ? 
                    \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') : '-',
                'method' => $payment->method ?: '-',
                'type' => $payment->type ?: '-',
                'reference_no' => $payment->reference_no ?: '-',
                'status' => 'completed',
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'academic_term' => $activeTerm->getFullNameAttribute(),
                'invoices' => $invoicesData,
                'payments' => $paymentsData,
                'summary' => [
                    'total_invoices' => $invoices->count(),
                    'total_invoice_amount' => $invoices->sum('total_amount'),
                    'total_paid' => $invoices->sum('paid_amount'),
                    'total_balance' => $invoices->sum('balance'),
                    'total_payments' => $payments->count(),
                    'total_payment_amount' => $payments->sum('amount'),
                ]
            ]
        ]);
    }

    /**
     * Get available academic terms for the student (terms they were enrolled in)
     */
    public function getAvailableTerms(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user->student) {
            return response()->json(['error' => 'User is not a student'], 403);
        }

        // Get all available academic terms (not just enrolled ones)
        $enrolledTerms = AcademicTerms::all()
            ->sortByDesc('year')
            ->sortByDesc('semester')
            ->values();

        $termsData = $enrolledTerms->map(function ($term) use ($user) {
            // Get invoices for this term with their relationships
            $invoices = \App\Models\Invoice::with(['items', 'payments'])
                ->where('student_id', $user->student->id)
                ->where('academic_term_id', $term->id)
                ->get();

            // Calculate unpaid invoices and amounts
            $unpaidInvoices = 0;
            $totalUnpaidAmount = 0;

            foreach ($invoices as $invoice) {
                $balance = $invoice->balance; // This uses the calculated attribute
                if ($balance > 0) {
                    $unpaidInvoices++;
                    $totalUnpaidAmount += $balance;
                }
            }

            return [
                'id' => $term->id,
                'name' => $term->getFullNameAttribute(),
                'year' => $term->year,
                'semester' => $term->semester,
                'is_active' => $term->is_active,
                'has_unpaid_invoices' => $unpaidInvoices > 0,
                'unpaid_invoices_count' => $unpaidInvoices,
                'total_unpaid_amount' => $totalUnpaidAmount,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $termsData
        ]);
    }

    /**
     * Calculate payment plan preview
     */
    public function calculatePaymentPlan(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'total_amount' => 'required|numeric|min:0',
            'down_payment' => 'required|numeric|min:0',
            'installment_months' => 'nullable|integer|min:1|max:12',
        ]);

        $installmentMonths = $validated['installment_months'] ?? 9;
        
        $calculation = \App\Models\PaymentPlan::calculate(
            $validated['total_amount'],
            $validated['down_payment'],
            $installmentMonths
        );

        // Generate preview schedule
        $startDate = \Carbon\Carbon::now()->addMonth()->startOfMonth();
        $schedule = [
            [
                'installment_number' => 0,
                'description' => 'Down Payment',
                'amount' => $calculation['down_payment_amount'],
                'due_date' => \Carbon\Carbon::now()->format('M d, Y'),
            ]
        ];

        for ($i = 1; $i <= $installmentMonths; $i++) {
            $amount = ($i === 1) ? $calculation['first_month_amount'] : $calculation['monthly_amount'];
            $dueDate = $startDate->copy()->addMonths($i - 1);
            
            $schedule[] = [
                'installment_number' => $i,
                'description' => $dueDate->format('F Y'), // e.g., "November 2025"
                'amount' => $amount,
                'due_date' => $dueDate->format('M d, Y'),
            ];
        }

        return response()->json([
            'success' => true,
            'plan' => $calculation,
            'schedule' => $schedule,
        ]);
    }

    /**
     * Student selects payment mode for their invoice
     */
    public function selectPaymentPlan(Request $request, $invoiceId): JsonResponse
    {
        $user = Auth::user();
        if (!$user->student) {
            return response()->json(['error' => 'User is not a student'], 403);
        }

        $validated = $request->validate([
            'payment_mode' => 'required|in:installment,full',
        ]);

        // Get invoice and verify it belongs to the student
        $invoice = Invoice::where('id', $invoiceId)
            ->where('student_id', $user->student->id)
            ->first();

        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        // Check if payment plan already exists
        if ($invoice->has_payment_plan) {
            return response()->json(['error' => 'Payment plan already selected for this invoice'], 400);
        }

        try {
            if ($validated['payment_mode'] === 'installment') {
                // Create installment payment plan
                $paymentPlanService = app(\App\Services\PaymentPlanService::class);
                
                // Fixed down payment for now (TODO: Get from admin settings)
                $downPayment = 4500;
                
                $paymentPlan = $paymentPlanService->createInstallmentPlan(
                    $invoice,
                    $downPayment,
                    9 // Fixed 9 months
                );

                return response()->json([
                    'success' => true,
                    'message' => 'Monthly installment plan created successfully',
                    'payment_plan' => [
                        'id' => $paymentPlan->id,
                        'payment_mode' => 'installment',
                        'down_payment' => $downPayment,
                        'monthly_amount' => $paymentPlan->monthly_amount,
                        'installment_months' => 9,
                    ],
                ]);
            } else {
                // Set payment mode to full
                $invoice->update([
                    'payment_mode' => 'full',
                    'has_payment_plan' => false,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'One-time payment mode selected',
                    'payment_mode' => 'full',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create payment plan',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Helper method to determine payment status
     */
    private function getPaymentStatus($invoice): string
    {
        if ($invoice->balance <= 0) {
            return 'paid';
        } elseif ($invoice->paid_amount > 0) {
            return 'partial';
        } else {
            return 'unpaid';
        }
    }
}
