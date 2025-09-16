<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = ['invoice_id', 'school_fee_id', 'amount', 'academic_term_id'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function fee()
    {
        return $this->belongsTo(SchoolFee::class, 'school_fee_id');
    }

    public function academicTerm()
    {
        return $this->belongsTo(AcademicTerms::class, 'academic_term_id');
    }
}
