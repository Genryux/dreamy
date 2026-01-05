<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampusTourSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'heading',
        'description',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the items for this section
     */
    public function items()
    {
        return $this->hasMany(CampusTourItem::class)->orderBy('order');
    }

    /**
     * Get the active section
     */
    public static function getActive()
    {
        return self::where('is_active', true)->orderBy('order')->first();
    }
}
