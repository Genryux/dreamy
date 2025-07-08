<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentSubmissions extends Model
{
    
    protected $fillable = [
        'academic_terms_id',
        'enrollment_period_id',
        'document_id',
        'applicants_id',
        'file_path',
        'status',
        'review_notes',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'file_path' => 'string',
    ];

    public function document()
    {
        return $this->belongsTo(Documents::class, 'documents_id');
    }

    public function applicant()
    {
        return $this->belongsTo(Applicants::class, 'applicants_id');
    }
}
