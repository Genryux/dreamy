<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlumniSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'heading',
        'description',
        'background_image',
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
        return $this->hasMany(AlumniItem::class)->orderBy('order');
    }

    /**
     * Get the active section
     */
    public static function getActive()
    {
        return self::where('is_active', true)->orderBy('order')->first();
    }

    /**
     * Get the background image URL
     */
    public function getBackgroundImageUrl()
    {
        if ($this->background_image && file_exists(public_path('storage/' . $this->background_image))) {
            return asset('storage/' . $this->background_image);
        }
        return asset('images/graduate.jpg');
    }
}
