<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentPlan extends Model
{
    protected $fillable = [
        'invoice_id',
        'total_amount',
        'down_payment_amount',
        'remaining_amount',
        'installment_months',
        'monthly_amount',
        'first_month_amount',
        'payment_type',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'down_payment_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'monthly_amount' => 'decimal:2',
        'first_month_amount' => 'decimal:2',
    ];

    /**
     * A payment plan belongs to an invoice.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * A payment plan has many payment schedules.
     */
    public function schedules()
    {
        return $this->hasMany(PaymentSchedule::class);
    }

    /**
     * Calculate the payment plan based on down payment.
     */
    public static function calculate($totalAmount, $downPayment, $installmentMonths = 9)
    {
        $remaining = $totalAmount - $downPayment;
        $monthlyAmount = round($remaining / $installmentMonths, 2);
        
        // Calculate total of all monthly payments
        $totalMonthly = $monthlyAmount * $installmentMonths;
        
        // Calculate difference due to rounding
        $difference = $remaining - $totalMonthly;
        
        // Add difference to first month
        $firstMonthAmount = $monthlyAmount + $difference;

        return [
            'total_amount' => $totalAmount,
            'down_payment_amount' => $downPayment,
            'remaining_amount' => $remaining,
            'installment_months' => $installmentMonths,
            'monthly_amount' => $monthlyAmount,
            'first_month_amount' => $firstMonthAmount,
        ];
    }
}

