<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentRecord extends Model
{
    protected $table = 'student_records';
    protected $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'middle_name',
        'birthdate',
        'gender',
        'age',
        'place_of_birth',

        'email',
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

        'grade_level',
        'program',
        'current_school',
        'previous_school',
        'school_contact_info',
        'acad_term_applied',
        'semester_applied',
        'admission_date',

        'has_special_needs',
        'belongs_to_ip',
        'is_4ps_beneficiary'
    ];

    public function getFullName()
    {
        return "{$this->last_name}, {$this->first_name} {$this->middle_name}";
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
