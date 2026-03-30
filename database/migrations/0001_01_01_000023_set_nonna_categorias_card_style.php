<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // nonna-reels: categories + card style
        DB::table('restaurants')
            ->where('slug', 'nonna-reels')
            ->update(['menu_layout' => 'categories', 'video_style' => 'card']);

        // nonna-categorias: categories + default (tiktok) style
        DB::table('restaurants')
            ->where('slug', 'nonna-categorias')
            ->update(['menu_layout' => 'categories', 'video_style' => 'default']);
    }

    public function down(): void {}
};
