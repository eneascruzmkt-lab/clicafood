<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $slugs = ['nonna-reels', 'nonna-categorias', 'nonna-stories'];

        DB::table('restaurants')
            ->whereIn('slug', $slugs)
            ->update([
                'secondary_color' => '#ffffff',
                'text_color' => '#1a1a1a',
                'text_secondary_color' => '#6b7280',
                'border_color' => '#e5e7eb',
                'price_color' => '#E63B2E',
            ]);
    }

    public function down(): void
    {
        $slugs = ['nonna-reels', 'nonna-categorias', 'nonna-stories'];

        DB::table('restaurants')
            ->whereIn('slug', $slugs)
            ->update([
                'secondary_color' => '#000000',
                'text_color' => '#ffffff',
                'text_secondary_color' => '#9ca3af',
                'border_color' => '#333333',
            ]);
    }
};
