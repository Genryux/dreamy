<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'status'
    ];

    public function programs() {
        return $this->hasMany(Program::class);
    }

}
