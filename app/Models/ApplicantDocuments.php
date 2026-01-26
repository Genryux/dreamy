<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantDocuments extends Model
{
    //

    protected $table = "applicants_documents";

    protected $fillable = [
        'applicants_id',
        'documents_id',
        'submit_before',
        'status'
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicants::class);
    }

    public function documents()
    {
        return $this->belongsTo(Documents::class);
    }

    public function submissions()
    {
        return $this->hasMany(DocumentSubmissions::class, 'documents_id', 'documents_id')
            ->where(function ($query) {
                $query->where('owner_id', $this->applicants_id)
                      ->where('owner_type', Applicants::class);
            })
            ->latest('submitted_at');
    }
}
