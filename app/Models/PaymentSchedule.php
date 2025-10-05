<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PaymentSchedule extends Model
{
    protected $fillable = [
        'payment_plan_id',
        'invoice_id',
        'installment_number',
        'amount_due',
        'amount_paid',
        'due_date',
        'status',
        'description',
    ];

    protected $casts = [
        'amount_due' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'due_date' => 'date',
    ];

    /**
     * A payment schedule belongs to a payment plan.
     */
    public function paymentPlan()
    {
        return $this->belongsTo(PaymentPlan::class);
    }

    /**
     * A payment schedule belongs to an invoice.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * A payment schedule has many payments.
     */
    public function payments()
    {
        return $this->hasMany(InvoicePayment::class);
    }

    /**
     * Get remaining balance for this schedule.
     */
    public function getRemainingAttribute()
    {
        return $this->amount_due - $this->amount_paid;
    }

    /**
     * Check if schedule is overdue.
     */
    public function isOverdue()
    {
        if (!$this->due_date || $this->status === 'paid') {
            return false;
        }

        return Carbon::parse($this->due_date)->isPast() && $this->status !== 'paid';
    }

    /**
     * Update status based on payment.
     */
    public function updateStatus()
    {
        if ($this->amount_paid >= $this->amount_due) {
            $this->status = 'paid';
        } elseif ($this->amount_paid > 0) {
            $this->status = 'partial';
        } elseif ($this->isOverdue()) {
            $this->status = 'overdue';
        } else {
            $this->status = 'pending';
        }

        $this->save();
    }
}

