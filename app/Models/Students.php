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
        'full_name',
        'age',
        'contact_number',
        'email_address',
        'grade_level',
        'enrollment_date',
        'status'
    ];

    public function record() {
        return $this->hasOne(StudentRecords::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

}
