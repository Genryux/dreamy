<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HowToApplySection extends Model
{
    protected $fillable = [
        'heading',
        'description',
        'button_text',
        'button_link',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the steps for this section
     */
    public function steps(): HasMany
    {
        return $this->hasMany(HowToApplyStep::class)->orderBy('order');
    }

    /**
     * Alias for steps() to maintain consistency with other sections
     */
    public function items(): HasMany
    {
        return $this->steps();
    }

    /**
     * Get the active section with steps
     */
    public static function getActive()
    {
        return self::with('steps')
            ->where('is_active', true)
            ->orderBy('order')
            ->first();
    }
}
