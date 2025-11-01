<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Student;
use App\Models\User;
use App\Notifications\PrivateImmediateNotification;
use App\Notifications\PrivateQueuedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Notification;
use Throwable;

class InvoiceController extends Controller
{
    public function getInvoices(Request $request)
    {
        $query = Invoice::query();

        // Search filter
        if ($search = $request->input('search.value')) {
            $query->whereHas('student.user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('lrn', 'like', "%{$search}%");
            })->orWhere('invoice_number', 'like', "%{$search}%");
        }

        if ($status = $request->input('status_filter')) {
            $query->where('status', $status);
        }

        if ($term = $request->input('term_filter')) {
            $query->where('academic_term_id', $term);
        }

        if ($method = $request->input('method_filter')) {
            if ($method === 'flexible') {
                // Show invoices with no payment plan (Not Set) - these have payment_mode = 'flexible'
                $query->where('payment_mode', 'flexible');
            } else {
                // Show invoices with specific payment plan type
                $query->whereHas('paymentPlan', fn($q) => $q->where('payment_type', $method));
            }
        }

        $total = $query->count();
        $filtered = $total;

        // Secure pagination with bounds
        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);
        $length = max(10, min($length, 100)); // Clamp to [10, 100] records per page

        $data = $query
            ->with(['student.user', 'items', 'paymentPlan', 'academicTerm'])
            ->offset($start)
            ->limit($length)
            ->get(['id', 'student_id', 'invoice_number', 'status', 'academic_term_id', 'payment_mode'])
            ->map(function ($item, $key) use ($start) {
                // Get current active academic term
                $currentTerm = \App\Models\AcademicTerms::where('is_active', true)->first();
                $isCurrentTerm = $currentTerm && $item->academic_term_id == $currentTerm->id;

                // Determine status badge color
                $statusBadge = $item->status;
                if ($item->status === 'unpaid' && !$isCurrentTerm) {
                    $statusBadge = 'overdue'; // Special status for unpaid invoices from previous terms
                }

                return [
                    'index' => $start + $key + 1,
                    'invoice_number' => $item->invoice_number ?? '-',
                    'student' => $item->student->user->last_name . ', ' .  $item->student->user->first_name ?? '-',
                    'status' => $statusBadge,
                    'payment_method' => $item->payment_mode ?? 'Not Set',
                    'academic_term' => $item->academicTerm ? $item->academicTerm->getFullNameAttribute() : 'Unknown',
                    'total' => '₱ ' . number_format($item->total_amount, 2) ?? '-',
                    'balance' => '₱ ' . number_format($item->balance, 2) ?? '-',
                    'id' => $item->id
                ];
            });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }

    public function getInvoiceHistory(Request $request)
    {
        $query = Invoice::onlyTrashed()->where('status', 'paid');

        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('student.user', function ($q2) use ($search) {
                    $q2->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%");
                })
                    ->orWhere('invoice_number', 'like', "%{$search}%")
                    ->orWhere('payment_mode', 'like', "%{$search}%");
            });
        }


        if ($status = $request->input('status_filter')) {
            $query->where('status', $status);
        }

        if ($term = $request->input('term_filter')) {
            $query->where('academic_term_id', $term);
        }

        if ($method = $request->input('method_filter')) {
            if ($method === 'flexible') {
                // Show invoices with no payment plan (Not Set) - these have payment_mode = 'flexible'
                $query->where('payment_mode', 'flexible');
            } else {
                // Show invoices with specific payment plan type
                $query->whereHas('paymentPlan', fn($q) => $q->where('payment_type', $method));
            }
        }

        $total = $query->count();
        $filtered = $total;

        // Secure pagination with bounds
        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);
        $length = max(10, min($length, 100)); // Clamp to [10, 100] records per page

        $data = $query
            ->with(['student.user', 'items', 'paymentPlan', 'academicTerm'])
            ->offset($start)
            ->limit($length)
            ->get(['id', 'student_id', 'invoice_number', 'status', 'academic_term_id', 'payment_mode'])
            ->map(function ($item, $key) use ($start) {
                // Get current active academic term
                $currentTerm = \App\Models\AcademicTerms::where('is_active', true)->first();
                $isCurrentTerm = $currentTerm && $item->academic_term_id == $currentTerm->id;

                // Determine status badge color
                $statusBadge = $item->status;
                if ($item->status === 'unpaid' && !$isCurrentTerm) {
                    $statusBadge = 'overdue'; // Special status for unpaid invoices from previous terms
                }

                return [
                    'index' => $start + $key + 1,
                    'invoice_number' => $item->invoice_number ?? '-',
                    'student' => $item->student->user->last_name . ', ' .  $item->student->user->first_name ?? '-',
                    'status' => $statusBadge,
                    'payment_method' => $item->paymentPlan ? $item->paymentPlan->payment_type : 'Not Set',
                    'academic_term' => $item->academicTerm ? $item->academicTerm->getFullNameAttribute() : 'Unknown',
                    'total' => '₱ ' . number_format($item->total_amount, 2) ?? '-',
                    'id' => $item->id
                ];
            });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }

    // Display a listing of invoices
    public function index()
    {
        $invoices = \App\Models\Invoice::all();
        return response()->json($invoices);
    }

    // Store a newly created invoice
    public function store(Request $request)
    {

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'school_fees' => 'required|array',
            'school_fee_amounts' => 'required|array'
        ]);


        try {
            $invoice = DB::transaction(function () use ($validated, $request) {
                // Get the active academic term
                $activeTerm = \App\Models\AcademicTerms::where('is_active', true)->first();
                $academicTermService = app(\App\Services\AcademicTermService::class);

                $currentTerm = $academicTermService->getCurrentAcademicTermData();

                if (!$activeTerm) {
                    throw new \Exception('No active academic term found. Please activate an academic term first.');
                }

                // Check if student already has an invoice for this academic term
                $existingInvoice = Invoice::withTrashed()
                    ->where('student_id', $validated['student_id'])
                    ->where('academic_term_id', $activeTerm->id)
                    ->where('status', 'paid')
                    ->first();

                if ($existingInvoice) {
                    throw new \Exception('This student have already paid his/her invoice for the current term and cannot be reassign a new one.');
                }

                // Create new invoice
                $invoice = Invoice::withTrashed()->updateOrCreate([
                    'student_id' => $validated['student_id'],
                    'academic_term_id' => $activeTerm->id,
                    'status' => 'unpaid'
                ]);

                foreach ($validated['school_fees'] as $feeId) {
                    $amount = $validated['school_fee_amounts'][$feeId] ?? 0;

                    InvoiceItem::firstOrCreate(
                        [
                            'invoice_id' => $invoice->id,
                            'school_fee_id' => $feeId,
                            'academic_term_id' => $activeTerm->id,
                        ],
                        [
                            'amount' => $amount
                        ]
                    );
                }

                $student = Student::find($validated['student_id']);
                $user = $student->user; // Get the user through the relationship
                $sharedNotificationId = 'monthly-reminder-' . time() . '-' . uniqid();

                $user->notify(new PrivateQueuedNotification(
                    "Invoice notification",
                    "Hi! An invoice for the academic term {$currentTerm['year']} - {$currentTerm['semester']} the has been assigned to you. You can settle it at your convenience; either one-time or in monthly installments.",
                    null,
                    $sharedNotificationId
                ));

                Notification::route('broadcast', 'user.' . $user->id)
                    ->notify(new PrivateImmediateNotification(
                        "Invoice notification",
                        "Hi! An invoice for the academic term {$currentTerm['year']} - {$currentTerm['semester']} the has been assigned to you. You can settle it at your convenience; either one-time or in monthly installments.",
                        null,
                        $sharedNotificationId,
                        'user.' . $user->id // Pass the channel
                    ));

                // Log the activity
                activity('financial_management')
                    ->causedBy(auth()->user())
                    ->performedOn($invoice)
                    ->withProperties([
                        'action' => 'assigned_invoice',
                        'invoice_id' => $invoice->id,
                        'invoice_number' => $invoice->invoice_number,
                        'student_id' => $validated['student_id'],
                        'student_name' => $student->user->first_name . ' ' . $student->user->last_name,
                        'academic_term_id' => $activeTerm->id,
                        'academic_term' => $currentTerm['year'] . ' ' . $currentTerm['semester'],
                        'school_fees_count' => count($validated['school_fees']),
                        'school_fee_ids' => $validated['school_fees'],
                        'total_amount' => $invoice->total_amount,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent()
                    ])
                    ->log('Invoice assigned to student');

                return $invoice; // Return the invoice from the transaction
            });

            // Calculate updated invoice counts for the current academic term
            $activeTerm = \App\Models\AcademicTerms::where('is_active', true)->first();
            $totalInvoices = Invoice::withTrashed()->where('academic_term_id', $activeTerm->id)->count();
            $pendingInvoices = Invoice::withTrashed()->where('academic_term_id', $activeTerm->id)->where('status', 'unpaid')->count();
            $paidInvoices = Invoice::withTrashed()->where('academic_term_id', $activeTerm->id)->where('status', 'paid')->count();
            $partiallyPaidInvoices = Invoice::withTrashed()->where('academic_term_id', $activeTerm->id)->where('status', 'partially_paid')->count();

            return response()->json([
                'success' => true,
                'message' => 'Invoice created successfully',
                'data' => $invoice,
                'totalInvoices' => $totalInvoices,
                'pendingInvoices' => $pendingInvoices,
                'paidInvoices' => $paidInvoices,
                'partiallyPaidInvoices' => $partiallyPaidInvoices
            ], 201);
        } catch (\Throwable $th) {
            \Log::error('Invoice creation failed', [
                'error' => $th->getMessage(),
                'user_id' => auth()->user()->id,
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'error' => 'Failed to create invoice: ' . $th->getMessage()
            ], 422);
        }
    }

    // Display the specified invoice
    public function show($id)
    {
        $invoice = Invoice::withTrashed()->with(['student', 'items.fee', 'payments.paymentSchedule', 'paymentPlan.schedules'])->findOrFail($id);

        $paymentPlanSummary = null;
        if ($invoice->has_payment_plan) {
            $paymentPlanService = app(\App\Services\PaymentPlanService::class);
            $paymentPlanSummary = $paymentPlanService->getPaymentPlanSummary($invoice);
        }

        // Get available discounts
        $availableDiscounts = \App\Models\Discount::active()->get();

        // Check if student is early enrollee
        // Only applies to students who actually went through the enrollment process for THIS invoice's academic term
        $isEarlyEnrollee = false;
        $earlyDiscountPercentage = 0;
        
        if ($invoice->student && $invoice->academicTerm) {
            // Get enrollment periods for the invoice's academic term
            $enrollmentPeriods = \App\Models\EnrollmentPeriod::where('academic_terms_id', $invoice->academic_term_id)
                ->where('period_type', 'early')
                ->where('early_discount_percentage', '>', 0)
                ->get();
            
            // Check if student has an Applicant record for any early enrollment period of this academic term
            foreach ($enrollmentPeriods as $enrollmentPeriod) {
                $hasApplicantRecord = \App\Models\Applicants::where('user_id', $invoice->student->user_id)
                    ->where('enrollment_period_id', $enrollmentPeriod->id)
                    ->exists();
                
                if ($hasApplicantRecord) {
                    $isEarlyEnrollee = true;
                    $earlyDiscountPercentage = $enrollmentPeriod->early_discount_percentage;
                    break; // Use the first matching enrollment period
                }
            }
        }

        return view('user-admin.invoice.show', [
            'invoice' => $invoice,
            'paymentPlanSummary' => $paymentPlanSummary,
            'availableDiscounts' => $availableDiscounts,
            'isEarlyEnrollee' => $isEarlyEnrollee,
            'earlyDiscountPercentage' => $earlyDiscountPercentage,
        ]);
    }

    // Update the specified invoice
    public function update(Request $request, $id)
    {
        $invoice = \App\Models\Invoice::findOrFail($id);

        $validated = $request->validate([
            'customer_name' => 'sometimes|required|string|max:255',
            'amount' => 'sometimes|required|numeric|min:0',
            'due_date' => 'sometimes|required|date',
        ]);

        $invoice->update($validated);
        return response()->json($invoice);
    }

    // Remove the specified invoice
    public function destroy($id)
    {
        $invoice = \App\Models\Invoice::findOrFail($id);
        $invoice->delete();
        return response()->json(['message' => 'Invoice deleted']);
    }

    /**
     * Download invoice for a specific payment schedule
     */
    public function downloadScheduleInvoice(Invoice $invoice, $scheduleId)
    {
        $schedule = $invoice->paymentSchedules()->findOrFail($scheduleId);

        // Load invoice with items and fee details
        $invoice->load(['items.fee', 'student.user', 'academicTerm']);

        // Get academic term - use invoice's term or fall back to current active term
        $academicTerm = $invoice->academicTerm;
        if (!$academicTerm) {
            $academicTermService = new \App\Services\AcademicTermService();
            $academicTerm = $academicTermService->fetchCurrentAcademicTerm();
        }

        // Calculate original admin-expected down payment
        $originalDownPayment = null;
        if ($schedule->installment_number === 0 && $invoice->has_payment_plan) {
            // For down payment, calculate what the admin originally expected
            $totalAmount = $invoice->paymentPlan->total_amount;
            $installmentMonths = $invoice->paymentPlan->installment_months;
            $remainingAmount = $totalAmount - $invoice->paymentPlan->down_payment_amount;
            $monthlyAmount = round($remainingAmount / $installmentMonths, 2);

            // If first month amount is different from monthly amount, 
            // it means there was a shortfall adjustment
            if ($invoice->paymentPlan->first_month_amount !== $monthlyAmount) {
                $shortfall = $invoice->paymentPlan->first_month_amount - $monthlyAmount;
                $originalDownPayment = $invoice->paymentPlan->down_payment_amount + $shortfall;
            } else {
                $originalDownPayment = $invoice->paymentPlan->down_payment_amount;
            }
        }

        // Generate PDF invoice
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('user-admin.invoice.schedule-invoice', [
            'invoice' => $invoice,
            'schedule' => $schedule,
            'student' => $invoice->student,
            'academicTerm' => $academicTerm,
            'schoolSettings' => \App\Models\SchoolSetting::first(),
            'originalDownPayment' => $originalDownPayment,
        ]);

        $filename = "Invoice-{$invoice->invoice_number}-{$schedule->description}.pdf";

        return $pdf->download($filename);
    }


    /**
     * Download receipt for a paid payment schedule
     */
    public function downloadScheduleReceipt(Invoice $invoice, $scheduleId)
    {
        $schedule = $invoice->paymentSchedules()->findOrFail($scheduleId);

        if ($schedule->status === 'pending') {
            abort(404, 'Receipt not available for unpaid schedules');
        }

        // Load invoice with items and fee details
        $invoice->load(['items.fee', 'student.user', 'academicTerm']);

        // Get academic term - use invoice's term or fall back to current active term
        $academicTerm = $invoice->academicTerm;
        if (!$academicTerm) {
            $academicTermService = new \App\Services\AcademicTermService();
            $academicTerm = $academicTermService->fetchCurrentAcademicTerm();
        }

        // Get payments for this schedule
        $payments = $schedule->payments()->orderBy('payment_date')->get();

        // Calculate original admin-expected down payment
        $originalDownPayment = null;
        if ($schedule->installment_number === 0 && $invoice->has_payment_plan) {
            // For down payment, calculate what the admin originally expected
            $totalAmount = $invoice->paymentPlan->total_amount;
            $installmentMonths = $invoice->paymentPlan->installment_months;
            $remainingAmount = $totalAmount - $invoice->paymentPlan->down_payment_amount;
            $monthlyAmount = round($remainingAmount / $installmentMonths, 2);

            // If first month amount is different from monthly amount, 
            // it means there was a shortfall adjustment
            if ($invoice->paymentPlan->first_month_amount !== $monthlyAmount) {
                $shortfall = $invoice->paymentPlan->first_month_amount - $monthlyAmount;
                $originalDownPayment = $invoice->paymentPlan->down_payment_amount + $shortfall;
            } else {
                $originalDownPayment = $invoice->paymentPlan->down_payment_amount;
            }
        }

        // Generate PDF receipt
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('user-admin.invoice.schedule-receipt', [
            'invoice' => $invoice,
            'schedule' => $schedule,
            'payments' => $payments,
            'student' => $invoice->student,
            'academicTerm' => $academicTerm,
            'schoolSettings' => \App\Models\SchoolSetting::first(),
            'originalDownPayment' => $originalDownPayment,
        ]);

        $filename = "Receipt-{$invoice->invoice_number}-{$schedule->description}.pdf";

        return $pdf->download($filename);
    }

    /**
     * Download one-time payment invoice PDF
     */
    public function downloadOneTimeInvoice(Invoice $invoice)
    {
        // Load necessary relationships
        $invoice->load(['items.fee', 'student.user', 'academicTerm']);

        // Get school settings
        $schoolSettings = \App\Models\SchoolSetting::first();

        // Get current academic term if not set
        $academicTerm = $invoice->academicTerm;
        if (!$academicTerm) {
            $academicTermService = new \App\Services\AcademicTermService();
            $academicTerm = $academicTermService->fetchCurrentAcademicTerm();
        }

        $pdf = Pdf::loadView('user-admin.invoice.onetime-invoice', [
            'invoice' => $invoice,
            'student' => $invoice->student,
            'academicTerm' => $academicTerm,
            'schoolSettings' => $schoolSettings,
        ]);

        return $pdf->download("invoice-{$invoice->invoice_number}-onetime.pdf");
    }

    /**
     * Download one-time payment receipt PDF
     */
    public function downloadOneTimeReceipt(Invoice $invoice)
    {
        // Load necessary relationships
        $invoice->load(['items.fee', 'student.user', 'academicTerm', 'payments']);

        // Get school settings
        $schoolSettings = \App\Models\SchoolSetting::first();

        // Get current academic term if not set
        $academicTerm = $invoice->academicTerm;
        if (!$academicTerm) {
            $academicTermService = new \App\Services\AcademicTermService();
            $academicTerm = $academicTermService->fetchCurrentAcademicTerm();
        }

        $pdf = Pdf::loadView('user-admin.invoice.onetime-receipt', [
            'invoice' => $invoice,
            'student' => $invoice->student,
            'academicTerm' => $academicTerm,
            'schoolSettings' => $schoolSettings,
        ]);

        return $pdf->download("receipt-{$invoice->invoice_number}-onetime.pdf");
    }

    /**
     * Remove an invoice item (school fee) from an invoice
     * Only allowed if no payment plan has been selected and no payments have been made
     */
    public function removeInvoiceItem(Invoice $invoice, $item)
    {
        try {

            // Check if already paid
            if ($invoice->status === 'paid') {
                return response()->json([
                    'success' => false,
                    'is_paid' => true,
                    'message' => "Cannot remove an invoice item from a paid invoice."
                ]);
            }
            // Check if payment plan exist
            if ($invoice->paymentPlan()->exists()) {
                return response()->json([
                    'success' => false,
                    'has_payment_plan' => true,
                    'message' => 'Cannot remove invoice item. Student has already selected a payment plan. Please contact the student to modify their payment plan first.'
                ], 422);
            }
            // check if payments exist
            if ($invoice->payments()->exists()) {
                return response()->json([
                    'success' => false,
                    'has_paymens' => true,
                    'message' => 'Cannot remove invoice item. Payments have already been made for this invoice. Please process a refund first if needed.'
                ], 422);
            }

            // Find the invoice item with fee relationship
            $invoiceItem = InvoiceItem::with('fee')->find($item);

            if (!$invoiceItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice item not found.'
                ], 404);
            }

            // Verify the item belongs to this invoice
            if ($invoiceItem->invoice_id !== $invoice->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice item does not belong to this invoice.'
                ], 403);
            }

            // Store item details for response
            $itemName = $invoiceItem->fee ? $invoiceItem->fee->name : 'Unknown Fee';
            $itemAmount = $invoiceItem->amount;

            // Delete the invoice item
            $invoiceItem->delete();

            // Recalculate invoice totals
            $invoice->refresh();
            $totalAmount = $invoice->items()->sum('amount');
            $invoice->update([
                'total_amount' => $totalAmount,
                'balance' => $totalAmount - $invoice->paid_amount
            ]);

            if ($invoice->items()->count() > 0) {
                return response()->json([
                    'success' => true,
                    'is_invoice_empty' => false,
                    'message' => "Invoice item '{$itemName}' (₱" . number_format($itemAmount, 2) . ") has been successfully removed."
                ]);
            }

            if ($invoice->items()->count() === 0) {

                $invoice->forceDelete();

                return response()->json([
                    'success' => true,
                    'is_invoice_empty' => true,
                    'message' => "All items have been successfully removed. This invoice will be deleted shortly."
                ]);
            }

            // Log the activity
            activity('financial_management')
                ->causedBy(auth()->user())
                ->performedOn($invoice)
                ->withProperties([
                    'action' => 'removed_invoice_item',
                    'invoice_id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'student_id' => $invoice->student_id,
                    'student_name' => $invoice->student->user->first_name . ' ' . $invoice->student->user->last_name,
                    'item_id' => $item,
                    'fee_name' => $itemName,
                    'removed_amount' => $itemAmount,
                    'new_total_amount' => $invoice->fresh()->total_amount,
                    'remaining_items_count' => $invoice->items()->count(),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ])
                ->log('Invoice item removed');
        } catch (Throwable $e) {
            \Log::error('Invoice item removal failed', [
                'error' => $e->getMessage(),
                'invoice_id' => $invoice->id,
                'item_id' => $item,
                'user_id' => auth()->user()->id,
                'ip_address' => request()->ip()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to remove invoice item: ' . $e->getMessage()
            ], 422);
        }
    }
}
