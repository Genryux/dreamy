<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //
    protected $table = 'students';
    protected $fillable = [
        'user_id',
        'enrollment_period_id',
        'track_id',
        'program_id',
        'section_id',
        'section',
        'lrn',
        'grade_level',
        'academic_status',
        'enrollment_date',
        'status'
    ];

    public function getFullNameAttribute()
    {
        return "{$this->user->last_name}, {$this->user->first_name}";
    }

    public function record()
    {
        return $this->hasOne(StudentRecord::class);
    }

    /**
     * Student belongs to a section.
     */
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function assignedDocuments()
    {
        return $this->hasMany(StudentDocument::class);
    }

    public function submissions()
    {
        return $this->morphMany(DocumentSubmissions::class, 'owner');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Student enrolled subjects (pivot)
    public function studentSubjects()
    {
        return $this->hasMany(StudentSubject::class);
    }

    public function sectionSubjects()
    {
        return $this->belongsToMany(SectionSubject::class, 'student_subjects')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function invoices() {
        return $this->hasMany(Invoice::class);
    }

    public function enrollments()
    {
        return $this->hasMany(StudentEnrollment::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function enrollmentPeriod()
    {
        return $this->belongsTo(EnrollmentPeriod::class);
    }

    /**
     * Get the current active academic term for this student
     */
    public function getCurrentAcademicTerm()
    {
        return $this->enrollments()
            ->whereHas('academicTerm', function($query) {
                $query->where('is_active', true);
            })
            ->with('academicTerm')
            ->first();
    }

    /**
     * Get the latest academic term for this student
     */
    public function getLatestAcademicTerm()
    {
        return $this->enrollments()
            ->with('academicTerm')
            ->latest('enrolled_at')
            ->first();
    }

    /**
     * Get all academic terms for this student
     */
    public function getAllAcademicTerms()
    {
        return $this->enrollments()
            ->with('academicTerm')
            ->orderBy('enrolled_at', 'desc')
            ->get();
    }

    /**
     * Get the current section for this student with fallback logic
     * Uses section_id from students table as default (for graduated/historical students)
     * Falls back to current enrollment's section for active students
     */
    public function getCurrentSection()
    {
        // Default: Use section_id from students table (shows historical section for graduated students)
        if ($this->section_id) {
            return $this->section;
        }

        // Fallback: For active students without section_id in students table, use current enrollment
        $currentEnrollment = $this->getCurrentAcademicTerm();
        if ($currentEnrollment && $currentEnrollment->section_id) {
            return $currentEnrollment->section;
        }

        // Final fallback: Use latest enrollment's section
        $latestEnrollment = $this->getLatestAcademicTerm();
        if ($latestEnrollment && $latestEnrollment->section_id) {
            return $latestEnrollment->section;
        }

        return null;
    }

    /**
     * Get the current section name for this student with fallback logic
     */
    public function getCurrentSectionName()
    {
        $section = $this->getCurrentSection();
        return $section ? $section->name : 'N/A';
    }
}
