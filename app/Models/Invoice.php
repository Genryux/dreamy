<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = ['student_id', 'academic_term_id', 'status', 'invoice_number', 'has_payment_plan', 'payment_mode'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            $invoice->invoice_number = self::generateInvoiceNumber();
        });
    }

    public static function generateInvoiceNumber()
    {
        $date = now()->format('Ymd'); // e.g. 20250903
        $prefix = "INV-{$date}";

        // Count invoices today
        $count = self::withTrashed()->whereDate('created_at', now()->toDateString())->count() + 1;

        // Pad with zeros (0001, 0002, etc.)
        $sequence = str_pad($count, 4, '0', STR_PAD_LEFT);

        return "{$prefix}-{$sequence}";
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->withTrashed()->where($field ?? 'id', $value)->firstOrFail();
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(InvoicePayment::class);
    }

    public function academicTerm()
    {
        return $this->belongsTo(AcademicTerms::class, 'academic_term_id');
    }

    public function paymentPlan()
    {
        return $this->hasOne(PaymentPlan::class);
    }

    public function paymentSchedules()
    {
        return $this->hasMany(PaymentSchedule::class);
    }

    public function getTotalAmountAttribute()
    {
        return $this->items()->sum('amount');
    }

    // 👇 Computed paid amount
    public function getPaidAmountAttribute()
    {
        return $this->payments->sum('amount');
    }

    // 👇 Computed balance
    public function getBalanceAttribute()
    {
        // For installment plans, use the discounted total if payment plan exists
        if ($this->has_payment_plan && $this->paymentPlan) {
            $discountedTotal = $this->paymentPlan->discounted_total ?? $this->total_amount;
            $balance = $discountedTotal - $this->paid_amount;
        } else {
            $balance = $this->total_amount - $this->paid_amount;
        }
        // Round to 2 decimal places to avoid floating-point precision issues
        return round($balance, 2);
    }

    /**
     * Get next due schedule
     */
    public function getNextDueScheduleAttribute()
    {
        if (!$this->has_payment_plan) {
            return null;
        }

        return $this->paymentSchedules()
            ->where('status', '!=', 'paid')
            ->orderBy('installment_number')
            ->first();
    }
}
