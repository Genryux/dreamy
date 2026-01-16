<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AcademicTerms;

class StudentSubject extends Model
{
    protected $table = 'student_subjects';

    protected $fillable = [
        'student_id',
        'section_subject_id',
        'teacher_id',
        'subject_id',
        'academic_terms_id',
        'status',
        'evaluation_status',
        'remedial_status',
        'remedial_deadline',
        'finalized_at',
        'is_remedial_status_finalized'
    ];

    protected $attributes = [
        'is_remedial_status_finalized' => false,
    ];

    protected $casts = [
        'remedial_deadline' => 'datetime',
        'finalized_at' => 'datetime',
        'is_remedial_status_finalized' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function sectionSubject()
    {
        return $this->belongsTo(SectionSubject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function academicTerm()
    {
        return $this->belongsTo(AcademicTerms::class, 'academic_terms_id');
    }
}
