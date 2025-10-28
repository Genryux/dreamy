<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $table = 'sections';

    protected $fillable = [
        'name',
        'program_id',
        'teacher_id',
        'year_level',
        'room',
        'total_enrolled_students',
    ];

    /**
     * Section belongs to a program.
     */
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Section has many students.
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'section_id');
    }

    /**
     * Section has many enrollments.
     */
    public function enrollments()
    {
        return $this->hasMany(StudentEnrollment::class, 'section_id');
    }

    /**
     * Section can have a teacher as adviser (nullable).
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    // Subjects offered in this section
    public function sectionSubjects()
    {
        return $this->hasMany(SectionSubject::class);
    }

    /**
     * Check if section is full (assuming a limit).
     */
    public function isFull($limit = 50)
    {
        return $this->total_enrolled_students >= $limit;
    }

    public function countStudents()
    {
        return $this->students()->count();
    }
}
