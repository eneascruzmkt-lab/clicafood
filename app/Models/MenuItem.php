<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'video_url',
        'video_thumbnail',
        'featured',
        'available',
        'order',
        'views_count',
        'video_plays_count',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'featured' => 'boolean',
            'available' => 'boolean',
            'order' => 'integer',
            'views_count' => 'integer',
            'video_plays_count' => 'integer',
        ];
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function analytics()
    {
        return $this->hasMany(Analytic::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) return null;
        if (str_starts_with($this->image, 'http')) return $this->image;
        return \Illuminate\Support\Facades\Storage::disk('s3')->url($this->image);
    }

    public function getVideoUrlFullAttribute(): ?string
    {
        if (!$this->video_url) return null;
        if (str_starts_with($this->video_url, 'http')) return $this->video_url;
        return \Illuminate\Support\Facades\Storage::disk('s3')->url($this->video_url);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'R$ ' . number_format($this->price, 2, ',', '.');
    }

    public function hasVideo(): bool
    {
        return !empty($this->video_url);
    }
}
