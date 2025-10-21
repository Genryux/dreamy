<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'name',
        'description', 
        'discount_type',
        'discount_value',
        'is_active'
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    /**
     * Calculate the discount amount for a given base amount
     */
    public function calculateDiscount($amount)
    {
        if ($this->discount_type === 'percentage') {
            return $amount * ($this->discount_value / 100);
        } else { // 'fixed'
            return min($this->discount_value, $amount); // Can't exceed the amount
        }
    }

    /**
     * Get formatted discount value for display
     */
    public function getFormattedValue()
    {
        if ($this->discount_type === 'percentage') {
            return $this->discount_value . '%';
        } else {
            return '₱' . number_format($this->discount_value, 2);
        }
    }

    /**
     * Scope for active discounts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
