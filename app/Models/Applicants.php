<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicants extends Model
{
    /** @use HasFactory<\Database\Factories\ApplicantFactory> */
    use HasFactory;

    protected $table = "applicants";
    protected $fillable = [
        'user_id',
        'applicant_id',
        'first_name',
        'last_name',
        'application_status',
    ];

    public function applicationForm()
    {
        return $this->hasOne(ApplicationForm::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->last_name}, {$this->first_name}";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function interview()
    {
        return $this->hasOne(Interview::class);
    }

    public function enrollmentPeriods()
    {
        return $this->belongsTo(EnrollmentPeriod::class);
    }

    public function academicTerms()
    {
        return $this->belongsTo(AcademicTerms::class);
    }

    public function submissions()
    {
        return $this->hasMany(DocumentSubmissions::class);
    }

    public function documents()
    {
        return $this->belongsToMany(Documents::class, 'applicants_documents');
    }

    public function scopeWithAnyStatus($query, $status = [])
    {
        return $query->whereIn('application_status', $status);
    }

    public function scopeWithStatus($query, $status)
    {
        return $query->where('application_status', $status);
    }

    protected static function booted()
    {
        static::creating(function ($applicant) {
            $now = now();

            $prefix = 'DAP';
            $date = $now->format('ymd'); // '250624'
            $count = self::whereDate('created_at', $now->toDateString())->count() + 1;
            $sequence = str_pad($count, 3, '0', STR_PAD_LEFT); // '001', '002', etc.

            $applicant->applicant_id = "$prefix-$date-$sequence";
        });
    }
}
