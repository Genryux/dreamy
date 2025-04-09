<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationForm extends Model
{
    //
    protected $table = "application_forms";
    protected $fillable = [
        'applicant_id','lrn', 'full_name', 'age', 'birthdate', 'desired_program', 'grade_level'
    ];

    public function applicant() {
        return $this->belongsTo(Applicant::class);
    }
}
