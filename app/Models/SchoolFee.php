<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolFee extends Model
{
    protected $fillable = ['name', 'amount', 'program_id', 'grade_level'];

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
