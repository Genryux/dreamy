<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoicePayment extends Model
{
    protected $fillable = [
        'invoice_id',
        'payment_schedule_id',
        'amount',
        'original_amount',
        'early_discount',
        'custom_discounts',
        'total_discount',
        'payment_date',
        'method',
        'type',
        'reference_no',
        'academic_term_id',
    ];

    protected $casts = [
        'original_amount' => 'decimal:2',
        'early_discount' => 'decimal:2',
        'custom_discounts' => 'decimal:2',
        'total_discount' => 'decimal:2',
        'amount' => 'decimal:2'
    ];

    protected static function booted()
    {
        static::creating(function ($payment) {
            if (empty($payment->reference_no)) {
                $payment->reference_no = 'PAY-' . now()->format('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * A payment belongs to an invoice.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function academicTerm()
    {
        return $this->belongsTo(AcademicTerms::class, 'academic_term_id');
    }

    /**
     * A payment can belong to a payment schedule.
     */
    public function paymentSchedule()
    {
        return $this->belongsTo(PaymentSchedule::class);
    }

    /**
     * Get the discount breakdown for this payment
     */
    public function getDiscountBreakdown()
    {
        return [
            'original_amount' => $this->original_amount,
            'early_discount' => $this->early_discount,
            'custom_discounts' => $this->custom_discounts,
            'total_discount' => $this->total_discount,
            'final_amount' => $this->amount
        ];
    }
}
