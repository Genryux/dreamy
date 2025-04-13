<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    /** @use HasFactory<\Database\Factories\ApplicantFactory> */
    use HasFactory;

    protected $table = "applicant";
    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'application_status',
    ];

    public function applicationForm() {
        return $this->hasOne(ApplicationForm::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function interview() {
        return $this->hasOne(Interview::class);
    }

}
