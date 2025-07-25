<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentRecords extends Model
{
    protected $table = 'student_records';
    protected $fillable = [
        'students_id',
        'first_name',
        'last_name',
        'middle_name',
        'birthdate',
        'age',
        'place_of_birth',

        'email',
        'current_address',
        'permanent_address',

        'father_name',
        'father_contact_number',
        'mother_name',
        'mother_contact_number',
        'guardian_name',
        'guardian_contact_number',

        'semester',
        'current_school',
        'previous_school',
        'school_contact_info',

        'has_special_needs',
        'belong_to_ip',
        'is_4ps_beneficiary'
    ];

    public function getFullName() {
        return "{$this->last_name}, {$this->first_name} {$this->middle_name}";
    }

    public function record()
    {
        return $this->belongsTo(Students::class);
    }


}
