<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('video_style', 20)->default('default')->after('menu_layout');
        });

        // Migrate reels_card layout back to reels + video_style card
        DB::table('restaurants')
            ->where('menu_layout', 'reels_card')
            ->update([
                'menu_layout' => 'reels',
                'video_style' => 'card',
            ]);
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn('video_style');
        });
    }
};
