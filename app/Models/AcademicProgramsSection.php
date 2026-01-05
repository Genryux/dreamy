<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicProgramsSection extends Model
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
        return $this->hasMany(AcademicProgramsItem::class, 'academic_programs_section_id')->orderBy('order');
    }

    /**
     * Get the active section with items
     */
    public static function getActive()
    {
        return self::with('items')
            ->where('is_active', true)
            ->orderBy('order')
            ->first();
    }
}
