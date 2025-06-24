<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    /** @use HasFactory<\Database\Factories\ApplicantFactory> */
    use HasFactory;

    protected $table = "applicant";
    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'application_status',
    ];

    public function applicationForm() {
        return $this->hasOne(ApplicationForm::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function interview() {
        return $this->hasOne(Interview::class);
    }

    public function enrollmentPeriods() {
        return $this->belongsTo(EnrollmentPeriod::class);
    }

    public function academicTerms() {
        return $this->belongsTo(AcademicTerms::class);
    }

    public function submissions() {
        return $this->hasMany(DocumentSubmissions::class);
    }

    public function documents() {
        return $this->belongsToMany(Documents::class);
    }

    public function scopeWithAnyStatus($query, $status = []) {
        return $query->whereIn('application_status', $status);
    }

    public function scopeWithStatus($query, $status) {
        return $query->where('application_status', $status);
    }

}
