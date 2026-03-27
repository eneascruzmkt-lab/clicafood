<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $slugs = ['nonna-reels', 'nonna-categorias', 'nonna-stories'];

        // Fix colors to beige theme matching original
        DB::table('restaurants')
            ->whereIn('slug', $slugs)
            ->update([
                'secondary_color' => '#f5ebe0',
                'text_color' => '#1a1a1a',
                'text_secondary_color' => '#7c6f64',
                'border_color' => '#e6d5c3',
                'price_color' => '#8B4513',
                'primary_color' => '#8B4513',
            ]);

        // Add images to categories that are missing them
        $restaurantIds = DB::table('restaurants')->whereIn('slug', $slugs)->pluck('id');

        foreach ($restaurantIds as $rid) {
            // Pratos Especiais - use Pasta Alla Contadina image
            DB::table('categories')
                ->where('restaurant_id', $rid)
                ->where('name', 'Pratos Especiais')
                ->update(['image' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/menu-items/vbq5nq6JOLqaHnICvT9XTfmOXpE2PPC54RspCgBx.jpg']);

            // Bebidas - use Coca-Cola image
            DB::table('categories')
                ->where('restaurant_id', $rid)
                ->where('name', 'Bebidas')
                ->update(['image' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/menu-items/XRQkD4wxWGNlJ6rC9XJpEQG5DmFURreyxpzuR2GO.jpg']);

            // Prato do Dia - use Fettuccine image
            DB::table('categories')
                ->where('restaurant_id', $rid)
                ->where('name', 'Prato do Dia')
                ->update(['image' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/menu-items/cAnLyFuPnfUoyHFlTvIeE3zMtCfKOJsJWDtiHBIl.jpg']);
        }
    }

    public function down(): void
    {
        // no-op
    }
};
