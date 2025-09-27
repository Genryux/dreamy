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
        'status',
    ];

    /**
     * A program has many sections.
     */
    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    /**
     * A program can have many subjects.
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    /**
     * Get total number of students across all sections in this program.
     */
    public function totalStudents($code)
    {
        $query = Student::query();

        if ($code) {
            $query->where('program', $code);
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
