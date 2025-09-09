<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            ->offset($start)
            ->limit($request->length)
            ->get(['id', 'student_id', 'invoice_number', 'status', ])
            ->map(function ($item, $key) use ($start) {
                // dd($item);
                return [
                    'index' => $start + $key + 1,
                    'invoice_number' => $item->invoice_number ?? '-',
                    'student' => $item->student->last_name . ', ' .  $item->student->first_name ?? '-',
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
                $invoice = Invoice::updateOrCreate(
                    [
                        'student_id' => $validated['student_id'],
                        'status'     => 'unpaid'   // condition: only 1 unpaid invoice per student
                    ],
                    [
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
        $invoice = Invoice::with(['student', 'items.fee', 'payments'])->findOrFail($id);
        return view('user-admin.invoice.show', [
            'invoice' => $invoice,
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
}
