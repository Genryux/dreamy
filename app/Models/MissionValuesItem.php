<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionValuesItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'mission_values_section_id',
        'icon',
        'title',
        'description',
        'color',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Get the section this item belongs to
     */
    public function section()
    {
        return $this->belongsTo(MissionValuesSection::class, 'mission_values_section_id');
    }
}
