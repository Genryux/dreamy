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
    public function totalStudents()
    {
        return $this->sections()->sum('total_enrolled_students');
    }
}
