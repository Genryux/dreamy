<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'track',
        'track_id',
        'status',
    ];

    /**
     * A program has many sections.
     */
    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function applicants() {
        return $this->hasMany(Applicants::class);
    }

    /**
     * A program can have many subjects.
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function track() {
        return $this->belongsTo(Track::class);
    }

    public function teachers() {
        return $this->hasMany(Teacher::class);
    }

    public function totalTeachers() {
        return $this->teachers()->count();
    }

    /**
     * Get total number of students across all sections in this program.
     */
    public function totalStudents($id)
    {
        $query = Student::query();

        if ($id) {
            $query->where('program_id', $id);
        }

        return $query->count();
    }

    public function schoolFees()
    {
        return $this->hasMany(SchoolFee::class);
    }

    public function getTotalSections()
    {
        return $this->sections()->count();
    }

    public function getTotalSubjects()
    {
        return $this->subjects()->count();
    }
}
