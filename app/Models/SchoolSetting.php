<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'address_line1',
        'address_line2',
        'city',
        'province',
        'country',
        'zip',
        'phone',
        'email',
        'website',
        'registrar_name',
        'registrar_title',
        'logo_path',
        // Financial/Payments
        'down_payment',
        'due_day_of_month',
        'use_last_day_if_shorter',
    ];
}


