<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrollmentPeriod extends Model
{
    protected $table = 'enrollment_periods';
    protected $fillable = [
        'academic_terms_id', 'name', 'application_start_date', 'application_end_date', 'max_applicants', 'status', 'active',
        'period_type', 'early_discount_percentage', 'period_for'
    ];

    protected $casts = [
        'early_discount_percentage' => 'decimal:2'
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

    public function studentEnrollments() {
        return $this->hasMany(StudentEnrollment::class);
    }

    /**
     * Check if this period is for new applicants
     */
    public function isForNewApplicants()
    {
        return $this->period_for === 'new';
    }

    /**
     * Check if this period is for continuing/old students
     */
    public function isForContinuingStudents()
    {
        return $this->period_for === 'old';
    }

    /**
     * Check if this is an early enrollment period
     */
    public function isEarlyEnrollment()
    {
        return $this->period_type === 'early' && $this->early_discount_percentage > 0;
    }

    /**
     * Calculate early enrollment discount for a given amount
     */
    public function calculateEarlyDiscount($amount)
    {
        if ($this->isEarlyEnrollment()) {
            return $amount * ($this->early_discount_percentage / 100);
        }
        return 0;
    }
}
