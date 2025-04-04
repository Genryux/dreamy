<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationForm extends Model
{
    //
    protected $table = "application_forms";
    protected $fillable = [
        'lrn', 'full_name', 'age', 'birthdate', 'desired_program', 'grade_level'
    ];
}
