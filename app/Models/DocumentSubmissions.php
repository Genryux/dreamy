<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentSubmissions extends Model
{
    
    protected $fillable = [
        'academic_terms_id',
        'enrollment_period_id',
        'document_id',
        'applicant_id',
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
        return $this->belongsTo(Documents::class, 'document_id');
    }

    public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }
}
