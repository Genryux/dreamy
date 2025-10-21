<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentPlan extends Model
{
    protected $fillable = [
        'invoice_id',
        'total_amount',
        'discounted_total',
        'total_discount',
        'down_payment_amount',
        'remaining_amount',
        'installment_months',
        'monthly_amount',
        'first_month_amount',
        'payment_type',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discounted_total' => 'decimal:2',
        'total_discount' => 'decimal:2',
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
    public static function calculate($totalAmount, $downPayment, $installmentMonths = 9, $totalDiscount = 0)
    {
        // Apply discount to total amount first
        $discountedTotal = $totalAmount - $totalDiscount;
        
        // Calculate remaining balance after down payment
        $remaining = $discountedTotal - $downPayment;
        $monthlyAmount = round($remaining / $installmentMonths, 2);
        
        // Calculate total of all monthly payments
        $totalMonthly = $monthlyAmount * $installmentMonths;
        
        // Calculate difference due to rounding
        $difference = $remaining - $totalMonthly;
        
        // Add difference to first month
        $firstMonthAmount = $monthlyAmount + $difference;

        return [
            'total_amount' => $totalAmount,
            'discounted_total' => $discountedTotal,
            'total_discount' => $totalDiscount,
            'down_payment_amount' => $downPayment,
            'remaining_amount' => $remaining,
            'installment_months' => $installmentMonths,
            'monthly_amount' => $monthlyAmount,
            'first_month_amount' => $firstMonthAmount,
        ];
    }
}

