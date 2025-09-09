<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['student_id', 'status', 'invoice_number',];

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
        $count = self::whereDate('created_at', now()->toDateString())->count() + 1;

        // Pad with zeros (0001, 0002, etc.)
        $sequence = str_pad($count, 4, '0', STR_PAD_LEFT);

        return "{$prefix}-{$sequence}";
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
        return $this->total_amount - $this->paid_amount;
    }
}
