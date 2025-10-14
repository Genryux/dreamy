<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //
    protected $table = 'students';
    protected $fillable = [
        'user_id',
        'track_id',
        'program_id',
        'section_id',
        'lrn',
        'grade_level',
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
    public function sections()
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
}
