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
        'days_of_the_week',
        'category',
        'semester',
        'teacher_id',
        'start_time',
        'end_time',
    ];

    /**
     * A subject belongs to a program (nullable).
     */
    public function program()
    {
        return $this->belongsTo(Program::class)->withDefault();
    }

    /**
     * A subject belongs to a teacher (nullable).
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class)->withDefault();
    }

    /**
     * Get subject duration in minutes.
     */
    public function duration()
    {
        if ($this->start_time && $this->end_time) {
            return \Carbon\Carbon::parse($this->start_time)
                ->diffInMinutes(\Carbon\Carbon::parse($this->end_time));
        }
        return null;
    }

    /**
     * Check if subject is a core subject.
     */
    public function isCore()
    {
        return $this->category === 'core';
    }

    /**
     * Scope: filter by semester.
     */
    public function scopeForSemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }
}
