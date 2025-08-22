<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentSubmissions extends Model
{

    protected $table = "document_submissions";

    protected $fillable = [
        'academic_terms_id',
        'enrollment_period_id',
        'documents_id',
        'applicants_id',
        'file_path',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'file_path' => 'string',
    ];

    public function document()
    {
        return $this->belongsTo(Documents::class);
    }

    public function applicant()
    {
        return $this->belongsTo(Applicants::class, 'applicants_id');
    }

    public function applicantDocument()
    {
        return $this->belongsTo(ApplicantDocuments::class);
    }
}
