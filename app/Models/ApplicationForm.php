<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationForm extends Model
{
    //
    protected $table = "application_forms";
    protected $fillable = [
        'academic_terms_id', 'enrollment_period_id', 'applicant_id', 'lrn', 'full_name', 'age', 'birthdate', 'desired_program', 'grade_level'
    ];

    public function applicant() {
        return $this->belongsTo(Applicant::class);
    }

    public function enrollmentPeriod() {
        return $this->belongsTo(EnrollmentPeriod::class);
    }
}
