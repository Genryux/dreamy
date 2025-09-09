<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class InvoicePaymentController extends Controller
{
    public function store(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'method' => 'nullable|string|max:50',
            'type' => 'nullable|string|max:50',
            'reference_no' => 'nullable|string|max:100',
        ]);

        try {
            DB::transaction(function () use ($invoice, $validated) {
                InvoicePayment::create([
                    'invoice_id' => $invoice->id,
                    'amount' => $validated['amount'],
                    'payment_date' => $validated['payment_date'],
                    'method' => $validated['method'] ?? null,
                    'type' => $validated['type'] ?? null,
                    'reference_no' => $validated['reference_no'] ?? null,
                ]);

                $invoice->refresh();
                if ($invoice->balance <= 0) {
                    $invoice->status = 'paid';
                    $invoice->save();
                }
            });

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
