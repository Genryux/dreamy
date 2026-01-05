<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class HomepageNotice extends Model
{
    protected $fillable = [
        'message',
        'bg_color',
        'text_color',
        'link_url',
        'is_scrolling',
        'is_dismissible',
        'is_active',
        'starts_at',
        'ends_at',
        'order',
    ];

    protected $casts = [
        'is_scrolling' => 'boolean',
        'is_dismissible' => 'boolean',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * Get all currently active notices
     */
    public static function getActiveNotices()
    {
        $now = Carbon::now();
        
        return self::where('is_active', true)
            ->where(function ($query) use ($now) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', $now);
            })
            ->orderBy('order')
            ->get();
    }

    /**
     * Get the first active notice
     */
    public static function getActiveNotice()
    {
        return self::getActiveNotices()->first();
    }

    /**
     * Check if notice is currently within its scheduled time
     */
    public function isWithinSchedule(): bool
    {
        $now = Carbon::now();
        
        if ($this->starts_at && $this->starts_at > $now) {
            return false;
        }
        
        if ($this->ends_at && $this->ends_at < $now) {
            return false;
        }
        
        return true;
    }
}
