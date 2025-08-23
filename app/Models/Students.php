<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    //
    protected $table = 'students';
    protected $fillable = [
        'user_id',
        'section_id',
        'program_id',
        'lrn',
        'first_name',
        'last_name',
        'age',
        'program',
        'contact_number',
        'email_address',
        'grade_level',
        'enrollment_date',
        'status'
    ];

    public function getFullNameAttribute()
    {
        return "{$this->last_name}, {$this->first_name}";
    }

    public function record()
    {
        return $this->hasOne(StudentRecords::class);
    }

    public function assignedDocuments()
    {
        return $this->hasMany(StudentDocument::class);
    }

    public function submissions()
    {
        return $this->morphMany(DocumentSubmissions::class, 'owner');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
