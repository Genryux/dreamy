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
        'track_id',
        'program_id',
        'first_name',
        'last_name',
        'application_status',
        'accepted_at',
        'accepted_by',
        'rejection_reason',
        'rejection_remarks',
        'rejected_at',
        'rejected_by'
    ];

    public function applicationForm()
    {
        return $this->hasOne(ApplicationForm::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->last_name}, {$this->first_name}";
    }

    public function getDocumentStatusAttribute()
    {
        $totalDocs = $this->assignedDocuments->count();
        $submittedDocs = $this->assignedDocuments->whereNotIn('status', ['Pending', 'not-submitted'])->count();
        $overdueDocs = $this->assignedDocuments->where('submit_before', '<', now())->whereIn('status', ['Pending', 'not-submitted'])->count();
        
        if ($totalDocs == 0) return 'No Requirements';
        if ($submittedDocs == $totalDocs) return "Complete ({$submittedDocs}/{$totalDocs})";
        if ($overdueDocs > 0) return 'Overdue';
        
        return "Pending ({$submittedDocs}/{$totalDocs})";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function program() {
        return $this->belongsTo(Program::class);
    }

    public function track() {
        return $this->belongsTo(Track::class);
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
        return $this->morphMany(DocumentSubmissions::class, 'owner');
    }

    public function assignedDocuments() 
    {
        return $this->hasMany(ApplicantDocuments::class);
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
