<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolFee extends Model
{
    protected $fillable = ['name', 'amount', 'program_id', 'grade_level', 'academic_term_id'];

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function academicTerm()
    {
        return $this->belongsTo(AcademicTerms::class, 'academic_term_id');
    }
}
