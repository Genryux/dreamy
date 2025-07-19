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
        'grade_level',
        'enrollment_date',
        'status'
    ];

    public function record() {
        return $this->hasOne(StudentRecords::class);
    }

}
