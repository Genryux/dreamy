<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicTerms extends Model
{
    use HasFactory;

    protected $table = "academic_terms";
    protected $fillable = [
        'year', 'semester', 'start_date', 'end_date', 'is_active'
    ];

    public function getFullNameAttribute() {
        return "{$this->year} - {$this->semester}";
    }

    public function enrollmentPeriods() {
        return $this->hasMany(EnrollmentPeriod::class);
    }

    public function ActiveEnrollmentPeriod() {
        return $this->hasOne(EnrollmentPeriod::class)
            ->where('status', 'Ongoing')
            ->where('active', true);
    }
    public function scopeEnrollmentPeriodStatus($status) {
        return $this->hasOne(EnrollmentPeriod::class)
            ->where('status', $status);
    }
    public function scopePaused($query) {
        return $query->where('is_active', true)->first();
    }
    
    public function enrollments() {
        return $this->hasMany(StudentEnrollment::class, 'academic_term_id');
    }

    public function applicationForms() {
        return $this->hasMany(ApplicationForm::class, 'academic_terms_id');
    }

}
