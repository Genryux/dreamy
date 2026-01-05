<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReasonItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'reason_section_id',
        'title',
        'description',
        'image',
        'order',
    ];

    /**
     * Get the section that owns this item
     */
    public function section()
    {
        return $this->belongsTo(ReasonSection::class, 'reason_section_id');
    }

    /**
     * Get the image URL
     */
    public function getImageUrl()
    {
        if ($this->image && file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }
        // Fallback to original images
        return asset('images/grad.jpg');
    }
}
