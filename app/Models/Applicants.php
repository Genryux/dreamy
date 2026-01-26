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
        'enrollment_period_id',
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
        'rejected_by',
        'is_archived',
        'archive_type',
        'archived_at',
        'archived_by',
        'archive_reason',
        'restored_at',
        'restored_by'
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
        'archived_at' => 'datetime',
        'restored_at' => 'datetime',
        'is_archived' => 'boolean',
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

    public function enrollmentPeriod()
    {
        return $this->belongsTo(EnrollmentPeriod::class);
    }

    public function academicTerms()
    {
        return $this->belongsTo(AcademicTerms::class);
    }

    public function submissions()
    {
        return $this->morphMany(DocumentSubmissions::class, 'owner')
            ->orderByDesc('submitted_at');
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

    /**
     * Scope to get only active (non-archived) applicants
     */
    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    /**
     * Scope to get only archived applicants
     */
    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    /**
     * Scope to get manually archived applicants
     */
    public function scopeManuallyArchived($query)
    {
        return $query->where('is_archived', true)->where('archive_type', 'manual');
    }

    /**
     * Scope to get period-expired archived applicants
     */
    public function scopePeriodExpiredArchived($query)
    {
        return $query->where('is_archived', true)->where('archive_type', 'period_expired');
    }

    /**
     * Relationship: User who archived this applicant
     */
    public function archivedByUser()
    {
        return $this->belongsTo(User::class, 'archived_by');
    }

    /**
     * Relationship: User who restored this applicant
     */
    public function restoredByUser()
    {
        return $this->belongsTo(User::class, 'restored_by');
    }

    /**
     * Check if applicant is archived
     */
    public function isArchived(): bool
    {
        return $this->is_archived === true;
    }

    /**
     * Archive this applicant
     */
    public function archive(User $archivedBy, ?string $reason = null, string $type = 'manual'): void
    {
        $this->update([
            'is_archived' => true,
            'archive_type' => $type,
            'archived_at' => now(),
            'archived_by' => $archivedBy->id,
            'archive_reason' => $reason,
            'restored_at' => null,
            'restored_by' => null,
        ]);
    }

    /**
     * Restore this applicant from archive
     */
    public function restore(User $restoredBy): void
    {
        $this->update([
            'is_archived' => false,
            'restored_at' => now(),
            'restored_by' => $restoredBy->id,
        ]);
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
