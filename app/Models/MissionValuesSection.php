<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionValuesSection extends Model
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
        'order' => 'integer',
    ];

    /**
     * Get the items for this section
     */
    public function items()
    {
        return $this->hasMany(MissionValuesItem::class)->orderBy('order');
    }

    /**
     * Get the active mission values section
     */
    public static function getActive()
    {
        return self::where('is_active', true)
            ->with('items')
            ->orderBy('order')
            ->first();
    }
}
