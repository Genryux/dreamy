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
        'academic_terms_id',
        'status',
        'evaluation_status'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function sectionSubject()
    {
        return $this->belongsTo(SectionSubject::class);
    }

    public function academicTerm()
    {
        return $this->belongsTo(AcademicTerms::class, 'academic_terms_id');
    }
}
