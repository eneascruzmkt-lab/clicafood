<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'label',
        'style_config',
        'url',
    ];

    protected function casts(): array
    {
        return [
            'style_config' => 'array',
        ];
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
