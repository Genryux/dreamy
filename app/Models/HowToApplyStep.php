<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HowToApplyStep extends Model
{
    protected $fillable = [
        'how_to_apply_section_id',
        'step_number',
        'title',
        'description',
        'icon',
        'order',
    ];

    /**
     * Get the section this step belongs to
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(HowToApplySection::class, 'how_to_apply_section_id');
    }
}
