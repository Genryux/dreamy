<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Get all invoices for the authenticated student
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->student) {
            return response()->json(['error' => 'User is not a student'], 403);
        }

        $invoices = Invoice::with(['items.schoolFee', 'payments', 'academicTerm'])
            ->where('student_id', $user->student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $invoices->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'status' => $invoice->status,
                    'academic_term' => $invoice->academicTerm ? $invoice->academicTerm->getFullNameAttribute() : null,
                    'total_amount' => $invoice->total_amount,
                    'paid_amount' => $invoice->paid_amount,
                    'balance' => $invoice->balance,
                    'created_at' => $invoice->created_at->format('M j, Y'),
                    'items_count' => $invoice->items->count(),
                    'payments_count' => $invoice->payments->count(),
                ];
            })
        ]);
    }

    /**
     * Get specific invoice details
     */
    public function show($id)
    {
        $user = Auth::user();
        
        if (!$user->student) {
            return response()->json(['error' => 'User is not a student'], 403);
        }

        $invoice = Invoice::with(['items.schoolFee', 'payments', 'academicTerm'])
            ->where('student_id', $user->student->id)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'status' => $invoice->status,
                'academic_term' => $invoice->academicTerm ? $invoice->academicTerm->getFullNameAttribute() : null,
                'created_at' => $invoice->created_at->format('M j, Y g:i A'),
                'total_amount' => $invoice->total_amount,
                'paid_amount' => $invoice->paid_amount,
                'balance' => $invoice->balance,
                'items' => $invoice->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'description' => $item->schoolFee ? $item->schoolFee->name : 'Fee',
                        'amount' => $item->amount,
                    ];
                }),
                'payments' => $invoice->payments->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'amount' => $payment->amount,
                        'method' => $payment->method,
                        'reference_no' => $payment->reference_no,
                        'payment_date' => $payment->payment_date ? 
                            \Carbon\Carbon::parse($payment->payment_date)->format('M j, Y') : 
                            $payment->created_at->format('M j, Y'),
                    ];
                }),
            ]
        ]);
    }

    /**
     * Get payment history for the authenticated student
     */
    public function payments(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->student) {
            return response()->json(['error' => 'User is not a student'], 403);
        }

        $payments = InvoicePayment::with(['invoice', 'academicTerm'])
            ->whereHas('invoice', function ($query) use ($user) {
                $query->where('student_id', $user->student->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $payments->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'amount' => $payment->amount,
                    'method' => $payment->method,
                    'type' => $payment->type,
                    'reference_no' => $payment->reference_no,
                    'payment_date' => $payment->payment_date ? 
                        \Carbon\Carbon::parse($payment->payment_date)->format('M j, Y') : 
                        $payment->created_at->format('M j, Y'),
                    'invoice_number' => $payment->invoice->invoice_number,
                    'academic_term' => $payment->academicTerm ? $payment->academicTerm->getFullNameAttribute() : null,
                ];
            })
        ]);
    }

    /**
     * Get financial summary for the authenticated student
     */
    public function summary(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->student) {
            return response()->json(['error' => 'User is not a student'], 403);
        }

        $invoices = Invoice::where('student_id', $user->student->id)->get();
        
        $totalAmount = $invoices->sum('total_amount');
        $paidAmount = $invoices->sum('paid_amount');
        $balance = $invoices->sum('balance');
        
        $recentPayments = InvoicePayment::with(['invoice'])
            ->whereHas('invoice', function ($query) use ($user) {
                $query->where('student_id', $user->student->id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => [
                    'total_billed' => $totalAmount,
                    'total_paid' => $paidAmount,
                    'outstanding_balance' => $balance,
                    'invoices_count' => $invoices->count(),
                    'payments_count' => $recentPayments->count(),
                ],
                'recent_payments' => $recentPayments->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'amount' => $payment->amount,
                        'method' => $payment->method,
                        'reference_no' => $payment->reference_no,
                        'payment_date' => $payment->payment_date ? 
                            \Carbon\Carbon::parse($payment->payment_date)->format('M j, Y') : 
                            $payment->created_at->format('M j, Y'),
                        'invoice_number' => $payment->invoice->invoice_number,
                    ];
                }),
            ]
        ]);
    }
}
