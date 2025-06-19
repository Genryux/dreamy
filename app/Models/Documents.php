<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentsFactory> */
    use HasFactory;
    protected $fillable = [
        'type',
        'description',
        'file_type_restriction',
        'max_file_size',
    ];
    protected $casts = [
        'max_file_size' => 'integer',
    ];
    protected $table = 'documents';

    public function submissions()
    {
        return $this->hasMany(DocumentSubmissions::class);
    }

}
