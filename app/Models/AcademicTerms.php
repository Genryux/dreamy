<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicTerms extends Model
{
    use HasFactory;

    protected $table = "academic_terms";
    protected $fillable = [
        'year', 'semester', 'start_date', 'end_date', 'is_active'
    ];

    public function getFullNameAttribute() {
        return "{$this->year}, {$this->semester}";
    }

    public function enrollment_period() {
        return $this->hasMany(EnrollmentPeriod::class);
    }

    

}
