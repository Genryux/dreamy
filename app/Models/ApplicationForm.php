<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationForm extends Model
{
    //
    protected $table = "application_forms";
    protected $fillable = [
        'academic_terms_id',
        'enrollment_period_id',
        'applicants_id',
        'preferred_sched',
        'is_returning',
        'lrn',
        'grade_level',
        'primary_track',
        'secondary_track',
        'last_name',
        'first_name',
        'middle_name',
        'extension_name',
        'birthdate',
        'age',
        'place_of_birth',
        'mother_tongue',
        'belongs_to_ip',
        'is_4ps_beneficiary',

        'cur_house_no',
        'cur_street',
        'cur_barangay',
        'cur_city',
        'cur_province',
        'cur_country',
        'cur_zip_code',

        'perm_house_no',
        'perm_street',
        'perm_barangay',
        'perm_city',
        'perm_province',
        'perm_country',
        'perm_zip_code',

        'father_last_name',
        'father_first_name',
        'father_middle_name',
        'father_contact_number',
        'mother_last_name',
        'mother_first_name',
        'mother_middle_name',
        'mother_contact_number',
        'guardian_last_name',
        'guardian_first_name',
        'guardian_middle_name',
        'guardian_contact_number',
        'has_special_needs',
        'special_needs',
        
        'last_grade_level_completed',
        'last_school_attended',
        'last_school_year_completed',
        'school_id',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicants::class);
    }

    public function enrollmentPeriod()
    {
        return $this->belongsTo(EnrollmentPeriod::class);
    }

    public function fullName()
    {
        return "{$this->last_name}, {$this->first_name} {$this->middle_name}, {$this->extension_name}";
    }

    public function fatherFullName()
    {
        return "{$this->father_last_name}, {$this->father_first_name} {$this->father_middle_name}";
    }

    public function motherFullName()
    {
        return "{$this->mother_last_name}, {$this->mother_first_name} {$this->mother_middle_name}";
    }

    public function guardianFullName()
    {
        return "{$this->guardian_last_name}, {$this->guardian_first_name} {$this->guardian_middle_name}";
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
