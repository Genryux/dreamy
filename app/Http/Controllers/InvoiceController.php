<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function getInvoices(Request $request)
    {
        //dd($request->all());

        //return response()->json(['ewan' => $request->all()]);

        $query = Invoice::query();
        //dd($query);

        // search filter
        if ($search = $request->input('search.value')) {
            $query->whereAny(['name', 'year_level', 'room'], 'like', "%{$search}%");
        }

        // // Filtering
        // if ($program = $request->input('program_filter')) {
        //     $query->whereHas('record', fn($q) => $q->where('program', $program));
        // }

        if ($grade = $request->input('grade_filter')) {
            $query->where('year_level', $grade);
        }

        // // Sorting
        // // Column mapping: must match order of your <th> and JS columns
        // $columns = ['lrn', 'first_name', 'grade_level', 'program', 'contact_number', 'email_address'];

        // // Get sort column index and direction
        // $orderColumnIndex = $request->input('order.0.column');
        // $orderDir = $request->input('order.0.dir', 'asc');

        // // Map to actual column name
        // $sortColumn = $columns[$orderColumnIndex] ?? 'id';

        // // Apply sorting
        // $query->orderBy($sortColumn, $orderDir);

        $total = $query->count();
        $filtered = $total;

        // $limit = $request->input('length', 10);  // default to 10 per page
        // $offset = $request->input('start', 0);

        $start = $request->input('start', 0);

        $data = $query
            ->with(['student.user'])
            ->offset($start)
            ->limit($request->length)
            ->get(['id', 'student_id', 'invoice_number', 'status', ])
            ->map(function ($item, $key) use ($start) {
                // dd($item);
                return [
                    'index' => $start + $key + 1,
                    'invoice_number' => $item->invoice_number ?? '-',
                    'student' => $item->student->user->last_name . ', ' .  $item->student->user->first_name ?? '-',
                    'status' => $item->status,
                    'total' => '₱ ' . $item->total_amount ?? '-',
                    'balance' => '₱ ' . $item->balance ?? '-',
                    'id' => $item->id
                ];
            });

        //dd($data);

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

        DB::transaction(function () use ($validated) {
            try {
                // Get the active academic term
                $activeTerm = \App\Models\AcademicTerms::where('is_active', true)->first();
                
                if (!$activeTerm) {
                    throw new \Exception('No active academic term found. Please activate an academic term first.');
                }

                $invoice = Invoice::updateOrCreate(
                    [
                        'student_id' => $validated['student_id'],
                        'academic_term_id' => $activeTerm->id,
                        'status'     => 'unpaid'   // condition: only 1 unpaid invoice per student per term
                    ],
                    [
                        'academic_term_id' => $activeTerm->id,
                        'status' => 'unpaid'
                    ]
                );

                foreach ($validated['school_fees'] as $feeId) {
                    $amount = $validated['school_fee_amounts'][$feeId] ?? 0; // 👈 get amount using fee id

                    InvoiceItem::updateOrCreate(
                        [
                            'invoice_id'    => $invoice->id,
                            'school_fee_id' => $feeId,   // condition: fee already exists in invoice
                        ],
                        [
                            'academic_term_id' => $activeTerm->id,
                            'amount'        => $amount
                        ]
                    );
                }
                return response()->json([

                    'data' => $invoice,
                    201
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'error' => $th->getMessage()
                ]);
            }
        });
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
