<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //
    protected $table = 'students';
    protected $fillable = [
        'user_id',
        'section_id',
        'lrn',
        'first_name',
        'last_name',
        'age',
        'program',
        'contact_number',
        'email_address',
        'grade_level',
        'enrollment_date',
        'status'
    ];

    public function getFullNameAttribute()
    {
        return "{$this->last_name}, {$this->first_name}";
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
}
