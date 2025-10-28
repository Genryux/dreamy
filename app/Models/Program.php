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

    /**
     * Get track-specific icon class
     */
    public function getTrackIcon()
    {
        if (!$this->track) {
            return 'fi fi-rr-book';
        }

        $icons = [
            'STEM' => 'fi fi-rr-microscope',
            'ABM' => 'fi fi-rr-briefcase', 
            'HUMSS' => 'fi fi-rr-book',
            'GAS' => 'fi fi-rr-star',
            'TVL' => 'fi fi-rr-tools',
            'ICT' => 'fi fi-rr-computer'
        ];

        return $icons[$this->track->name] ?? 'fi fi-rr-book';
    }

    /**
     * Get track-specific gradient colors
     */
    public function getTrackGradient()
    {
        if (!$this->track) {
            return 'from-[#1A3165] to-[#2A4A7A]';
        }

        $gradients = [
            'STEM' => 'from-[#1A3165] to-[#2A4A7A]',
            'ABM' => 'from-[#C8A165] to-[#D4B876]',
            'HUMSS' => 'from-[#1A3165] to-[#2A4A7A]',
            'GAS' => 'from-[#C8A165] to-[#D4B876]',
            'TVL' => 'from-[#1A3165] to-[#2A4A7A]',
            'ICT' => 'from-[#C8A165] to-[#D4B876]'
        ];

        return $gradients[$this->track->name] ?? 'from-[#1A3165] to-[#2A4A7A]';
    }

    /**
     * Check if track uses gold color scheme
     */
    public function isGoldTrack()
    {
        if (!$this->track) {
            return false;
        }

        return in_array($this->track->name, ['ABM', 'GAS', 'ICT']);
    }
}
