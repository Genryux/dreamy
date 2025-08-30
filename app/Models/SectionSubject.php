<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SectionSubject extends Model
{
    protected $table = 'section_subjects';

    protected $fillable = [
        'section_id',
        'subject_id',
        'teacher_id',
        'room',
        'days_of_week',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'days_of_week' => 'array',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class)->withDefault();
    }

    // 🔑 Enrolled students in this subject offering
    public function studentSubjects()
    {
        return $this->hasMany(StudentSubject::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_subjects')
            ->withPivot('status')
            ->withTimestamps();
    }
}
