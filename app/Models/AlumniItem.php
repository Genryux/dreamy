<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlumniItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'alumni_section_id',
        'name',
        'photo',
        'class_year',
        'track',
        'quote',
        'order',
    ];

    /**
     * Get the section that owns this item
     */
    public function section()
    {
        return $this->belongsTo(AlumniSection::class, 'alumni_section_id');
    }

    /**
     * Get the photo URL
     */
    public function getPhotoUrl()
    {
        if ($this->photo && file_exists(public_path('storage/' . $this->photo))) {
            return asset('storage/' . $this->photo);
        }
        // Fallback to default alumni image
        return asset('images/alumni1.jpg');
    }

    /**
     * Get formatted class info (e.g., "Class of 2020 · STEM")
     */
    public function getClassInfo()
    {
        $parts = [];
        if ($this->class_year) {
            $parts[] = $this->class_year;
        }
        if ($this->track) {
            $parts[] = $this->track;
        }
        return implode(' · ', $parts);
    }
}
