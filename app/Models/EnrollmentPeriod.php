<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrollmentPeriod extends Model
{
    protected $table = 'enrollment_periods';
    protected $fillable = [
        'academic_terms_id', 'name', 'application_start_date', 'application_end_date', 'max_applicants', 'status', 'active'
    ];

    public function applications() {
        return $this->hasMany(ApplicationForm::class);
    }

    public function academicTerms() {
        return $this->belongsTo(AcademicTerms::class);
    }

    public function applicants() {
        return $this->hasMany(Applicants::class);
    }
}
