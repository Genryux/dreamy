<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentRecord extends Model
{
    protected $table = 'student_records';
    
    protected $casts = [
        'special_needs' => 'array',
        'has_special_needs' => 'boolean',
        'belongs_to_ip' => 'boolean',
        'is_4ps_beneficiary' => 'boolean',
        'birthdate' => 'date',
        'admission_date' => 'date',
    ];
    
    protected $fillable = [
        'student_id',
        'middle_name',
        'extension_name',
        'birthdate',
        'gender',
        'age',
        'place_of_birth',
        'mother_tongue',

        'contact_number',
        'current_address',
        'permanent_address',

        'house_no',
        'street',
        'barangay',
        'city',
        'province',
        'country',
        'zip_code',

        'father_name',
        'father_contact_number',
        'mother_name',
        'mother_contact_number',
        'guardian_name',
        'guardian_contact_number',

        'last_school_attended',
        'last_grade_level_completed',
        'school_id',
        'acad_term_applied',
        'semester_applied',
        'admission_date',

        'has_special_needs',
        'special_needs',
        'belongs_to_ip',
        'is_4ps_beneficiary'
    ];

    public function getFullName()
    {
        $middleName = $this->middle_name ? " {$this->middle_name}" : '';
        return "{$this->student->user->last_name}, {$this->student->user->first_name}{$middleName}";
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function currentAddress()
    {
        return "{$this->house_no} {$this->street}, {$this->barangay}, {$this->city}, {$this->province}, {$this->zip_code}, {$this->country}";
    }

    public function permanentAddress()
    {
        return "{$this->house_no} {$this->street}, {$this->barangay}, {$this->city}, {$this->province}, {$this->zip_code}, {$this->country}";
    }
}
