<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title',
        'content',
        'status',
        'visibility',
        'is_announcement',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeForStudents($query)
    {
        return $query->whereIn('visibility', ['students_only', 'both']);
    }

    public function scopeForPublic($query)
    {
        return $query->whereIn('visibility', ['public', 'both']);
    }

    public function scopeAnnouncements($query)
    {
        return $query->where('is_announcement', true);
    }

    public function scopeNews($query)
    {
        return $query->where('is_announcement', false);
    }
}
