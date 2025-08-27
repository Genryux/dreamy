<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentDocument extends Model
{

    protected $table = "student_documents";

    protected $fillable = [
        'student_id',
        'documents_id',
        'submit_before',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Applicants::class);
    }

    public function documents()
    {
        return $this->belongsTo(Documents::class);
    }

    public function submissions()
    {
        $submissionTable = (new DocumentSubmissions())->getTable();
        $localTable      = $this->getTable();

        return $this->hasMany(DocumentSubmissions::class, 'documents_id', 'documents_id')
            ->where('owner_type', Student::class)
            ->where('owner_id', $this->students_id);
    }
}
