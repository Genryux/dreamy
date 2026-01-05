<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampusTourItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'campus_tour_section_id',
        'title',
        'description',
        'image',
        'icon',
        'highlight',
        'order',
    ];

    /**
     * Get the section that owns this item
     */
    public function section()
    {
        return $this->belongsTo(CampusTourSection::class, 'campus_tour_section_id');
    }

    /**
     * Get the image URL
     */
    public function getImageUrl()
    {
        if ($this->image && file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }
        // Fallback to placeholder image
        return 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=800&q=80';
    }
}
