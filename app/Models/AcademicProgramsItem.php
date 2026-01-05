<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicProgramsItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_programs_section_id',
        'title',
        'description',
        'track_name',
        'gradient_from',
        'gradient_to',
        'link_url',
        'status',
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
        return $this->belongsTo(AcademicProgramsSection::class, 'academic_programs_section_id');
    }

    /**
     * Check if this is a gold track (ABM, HUMSS, etc.)
     */
    public function isGoldTrack()
    {
        $goldTracks = ['ABM', 'HUMSS', 'GAS'];
        return in_array(strtoupper($this->track_name ?? ''), $goldTracks);
    }
}
