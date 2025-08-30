<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'program_id',
        'grade_level',
        'category',
        'semester',
    ];

    /**
     * A subject belongs to a program (nullable).
     */
    public function program()
    {
        return $this->belongsTo(Program::class)->withDefault();
    }

    // Different offerings of this subject
    public function sectionSubjects()
    {
        return $this->hasMany(SectionSubject::class);
    }

    /**
     * Scope: filter by semester.
     */
    public function scopeForSemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }
}
