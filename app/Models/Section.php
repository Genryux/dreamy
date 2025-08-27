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

    public function students()
    {
        return $this->hasMany(Student::class, 'section_id', 'id');
    }

    /**
     * Section belongs to a teacher (nullable).
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class)->withDefault();
    }

    /**
     * A section can have many subjects (via program or direct assignment).
     * Optional: if subjects are tied directly to program, you can fetch from program.
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    /**
     * Check if section is full (assuming a limit).
     */
    public function isFull($limit = 50)
    {
        return $this->total_enrolled_students >= $limit;
    }
}
