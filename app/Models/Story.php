<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'title',
        'image',
        'order',
        'active',
        'views_count',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'order' => 'integer',
            'views_count' => 'integer',
        ];
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
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
