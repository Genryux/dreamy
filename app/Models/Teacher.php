<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'email_address',
        'contact_number',
        'specialization',
        'years_of_experience',
        'status',
    ];

    /**
     * Get the teacher's full name.
     */
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Teacher belongs to a user (nullable).
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
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
}
