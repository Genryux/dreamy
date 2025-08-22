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
        return $this->hasMany(DocumentSubmissions::class);
    }
}
