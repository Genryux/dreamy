<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $table = 'interviews';
    protected $fillable = [
        'applicants_id', 'teacher_id', 'date', 'time', 'location', 'add_info', 'status', 'remarks'
    ];

    public function applicant() {
        return $this->belongsTo(Applicants::class);
    }

}
