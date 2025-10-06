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

class InvoiceController extends Controller
{
    public function getInvoices(Request $request)
    {
        $query = Invoice::query();

        // Search filter
        if ($search = $request->input('search.value')) {
            $query->whereHas('student.user', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('lrn', 'like', "%{$search}%");
            })->orWhere('invoice_number', 'like', "%{$search}%");
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
            ->get(['id', 'student_id', 'invoice_number', 'status', 'academic_term_id'])
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

                if (!$activeTerm) {
                    throw new \Exception('No active academic term found. Please activate an academic term first.');
                }

                // Check if student already has an invoice for this academic term
                $existingInvoice = Invoice::where('student_id', $validated['student_id'])
                    ->where('academic_term_id', $activeTerm->id)
                    ->first();

                if ($existingInvoice) {
                    throw new \Exception('This student already has an invoice for the current academic term. Only one invoice per student per academic term is allowed.');
                }

                // Create new invoice
                $invoice = Invoice::create([
                    'student_id' => $validated['student_id'],
                    'academic_term_id' => $activeTerm->id,
                    'status' => 'unpaid'
                ]);

                foreach ($validated['school_fees'] as $feeId) {
                    $amount = $validated['school_fee_amounts'][$feeId] ?? 0;

                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'school_fee_id' => $feeId,
                        'academic_term_id' => $activeTerm->id,
                        'amount' => $amount
                    ]);
                }

                $student = Student::find($validated['student_id']);
                $user = $student->user; // Get the user through the relationship
                $sharedNotificationId = 'monthly-reminder-' . time() . '-' . uniqid();

                $user->notify(new PrivateQueuedNotification(
                    "Invoice notification",
                    "Hi! An invoice for the has been assigned to you. You can settle it at your convenience; either one-time or in monthly installments.",
                    null,
                    $sharedNotificationId
                ));

                Notification::route('broadcast', 'user.' . $user->id)
                    ->notify(new PrivateImmediateNotification(
                        "Invoice notification",
                        "Hi! An invoice for the has been assigned to you. You can settle it at your convenience; either one-time or in monthly installments.",
                        null,
                        $sharedNotificationId,
                        'user.' . $user->id // Pass the channel
                    ));

                // Audit logging for invoice creation (inside transaction)
                \Log::info('Invoice created', [
                    'invoice_id' => $invoice->id,
                    'student_id' => $validated['student_id'],
                    'created_by' => auth()->user()->id,
                    'created_by_email' => auth()->user()->email,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);

                return $invoice; // Return the invoice from the transaction
            });

            return response()->json([
                'success' => true,
                'message' => 'Invoice created successfully',
                'data' => $invoice
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
        $invoice = Invoice::with(['student', 'items.fee', 'payments', 'paymentPlan.schedules'])->findOrFail($id);

        $paymentPlanSummary = null;
        if ($invoice->has_payment_plan) {
            $paymentPlanService = app(\App\Services\PaymentPlanService::class);
            $paymentPlanSummary = $paymentPlanService->getPaymentPlanSummary($invoice);
        }

        return view('user-admin.invoice.show', [
            'invoice' => $invoice,
            'paymentPlanSummary' => $paymentPlanSummary,
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
}
