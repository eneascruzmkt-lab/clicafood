<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'logo',
        'primary_color',
        'secondary_color',
        'text_color',
        'text_secondary_color',
        'border_color',
        'price_color',
        'font_family',
        'background_image',
        'background_opacity',
        'address',
        'phone',
        'instagram',
        'whatsapp',
        'working_hours',
    ];

    protected function casts(): array
    {
        return [
            'working_hours' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Restaurant $restaurant) {
            if (empty($restaurant->slug)) {
                $restaurant->slug = Str::slug($restaurant->name);
                $count = static::where('slug', 'like', $restaurant->slug . '%')->count();
                if ($count > 0) {
                    $restaurant->slug .= '-' . ($count + 1);
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class)->orderBy('order');
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class)->orderBy('order');
    }

    public function stories()
    {
        return $this->hasMany(Story::class)->orderBy('order');
    }

    public function qrCodes()
    {
        return $this->hasMany(QrCode::class);
    }

    public function analytics()
    {
        return $this->hasMany(Analytic::class);
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo) return null;
        if (str_starts_with($this->logo, 'http')) return $this->logo;
        return \Illuminate\Support\Facades\Storage::disk('s3')->url($this->logo);
    }

    public function getPublicUrlAttribute(): string
    {
        return url('/' . $this->slug);
    }

    public function toArray()
    {
        $array = parent::toArray();
        $resolve = function ($path) {
            if (!$path) return null;
            if (str_starts_with($path, 'http')) return $path;
            return \Illuminate\Support\Facades\Storage::disk('s3')->url($path);
        };
        if (isset($array['logo'])) $array['logo'] = $resolve($array['logo']);
        if (isset($array['background_image'])) $array['background_image'] = $resolve($array['background_image']);
        return $array;
    }
}
