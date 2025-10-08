<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;

class InvoiceItemController extends Controller
{
    public function getInvoiceItems(Request $request, Invoice $invoice)
    {
        $query = InvoiceItem::query();

        $total = $query->count();
        $filtered = $total;

        // Secure pagination with bounds
        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);
        $length = max(10, min($length, 100)); // Clamp to [10, 100] records per page

        $data = $query
            ->with(['invoice', 'fee',])
            ->where('invoice_id', $invoice->id)
            ->offset($start)
            ->limit($length)
            ->get(['id', 'invoice_id', 'amount', 'school_fee_id', 'academic_term_id'])
            ->map(function ($item, $key) use ($start) {

                return [
                    'name' =>  $item->fee?->name ?? '-',
                    'amount' => number_format($item->amount, 2) ?? '-',
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
}
