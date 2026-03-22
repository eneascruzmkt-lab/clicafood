<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytic extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'restaurant_id',
        'menu_item_id',
        'event_type',
        'ip',
        'user_agent',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function scopeViews($query)
    {
        return $query->where('event_type', 'view');
    }

    public function scopeClicks($query)
    {
        return $query->where('event_type', 'click');
    }

    public function scopeVideoPlays($query)
    {
        return $query->where('event_type', 'video_play');
    }

    public function scopePeriod($query, string $period)
    {
        return match ($period) {
            'today' => $query->whereDate('created_at', today()),
            'week' => $query->where('created_at', '>=', now()->subWeek()),
            'month' => $query->where('created_at', '>=', now()->subMonth()),
            'year' => $query->where('created_at', '>=', now()->subYear()),
            default => $query,
        };
    }
}
