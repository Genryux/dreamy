<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolAtGlanceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_at_glance_section_id',
        'value',
        'label',
        'bg_color',
        'text_color',
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
        return $this->belongsTo(SchoolAtGlanceSection::class, 'school_at_glance_section_id');
    }
}
