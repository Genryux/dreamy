<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'program_id',
        'section_id',
        'employee_id',
        'first_name',
        'last_name',
        'email_address',
        'contact_number',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the teacher's full name.
     */
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Teacher belongs to a user (required).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Teacher belongs to a program (faculty).
     */
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Teacher can be assigned to sections.
     */
    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    /**
     * Teacher can teach section subjects.
     */
    public function sectionSubjects()
    {
        return $this->hasMany(SectionSubject::class);
    }

    /**
     * Check if teacher account is active.
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Generate a unique employee ID.
     */
    public static function generateEmployeeId()
    {
        do {
            $employeeId = 'TCH' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('employee_id', $employeeId)->exists());

        return $employeeId;
    }

    /**
     * Get teacher's email from user relationship.
     */
    public function getEmailAttribute()
    {
        return $this->user ? $this->user->email : $this->email_address;
    }
}
