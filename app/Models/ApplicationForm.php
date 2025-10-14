<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationForm extends Model
{
    //
    protected $table = "application_forms";
    
    protected $casts = [
        'special_needs' => 'array',
        'has_special_needs' => 'boolean',
        'belongs_to_ip' => 'boolean',
        'is_4ps_beneficiary' => 'boolean',
        'is_returning' => 'boolean',
        'birthdate' => 'date',
        'last_school_year_completed' => 'date',
        'admission_date' => 'datetime',
    ];
    
    protected $fillable = [
        'academic_terms_id',
        'enrollment_period_id',
        'applicants_id',

        'preferred_sched',//no need
        'is_returning', //no need (isama sa student record)
        'lrn', // m/n
        'grade_level', 
        'acad_term_applied', // m/n
        'semester_applied', // m/n
        'admission_date', // m/n

        'last_name', //meron nasa user model
        'first_name', //meron nasa user model
        'middle_name', //meron nasa user model
        'extension_name', //meron nasa user model
        'gender', // m/n
        'birthdate', // m/n
        'age', // m/n meron/need
        'place_of_birth', // m/n
        'mother_tongue', // w/n
        'belongs_to_ip', // m/n
        'is_4ps_beneficiary', // m/n
        'contact_number', // m/n

        'cur_house_no',  //m/n
        'cur_street', //m/n
        'cur_barangay', //m/n
        'cur_city', //m/n
        'cur_province', //m/n
        'cur_country', //m/n
        'cur_zip_code', //m/n

        'perm_house_no', //m/n
        'perm_street', //m/n
        'perm_barangay', //m/n
        'perm_city', //m/n
        'perm_province', //m/n
        'perm_country', //m/n
        'perm_zip_code', //m/n

        'father_last_name', //m/n
        'father_first_name', //m/n
        'father_middle_name', //m/n
        'father_contact_number', //m/n

        'mother_last_name', //m/n
        'mother_first_name', //m/n
        'mother_middle_name', //m/n
        'mother_contact_number', //m/n

        'guardian_last_name', //m/n
        'guardian_first_name', //m/n
        'guardian_middle_name', //m/n
        'guardian_contact_number', //m/n
        
        'has_special_needs',// m/n
        'special_needs', // w/n done

        'last_grade_level_completed', //w/n
        'last_school_attended',//m/n
        'last_school_year_completed', //w/n
        'school_id', // w/n
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicants::class, 'applicants_id');
    }

    public function enrollmentPeriod()
    {
        return $this->belongsTo(EnrollmentPeriod::class);
    }

    public function academicTerm()
    {
        return $this->belongsTo(AcademicTerms::class, 'academic_terms_id');
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
