<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'name',
        'image',
        'order',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class)->orderBy('order');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) return null;
        if (str_starts_with($this->image, 'http')) return $this->image;
        return \Illuminate\Support\Facades\Storage::disk('s3')->url($this->image);
    }

    public function toArray()
    {
        $array = parent::toArray();
        if (isset($array['image']) && $array['image'] && !str_starts_with($array['image'], 'http')) {
            $array['image'] = \Illuminate\Support\Facades\Storage::disk('s3')->url($array['image']);
        }
        return $array;
    }
}
