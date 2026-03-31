<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->string('model_glb_url')->nullable()->after('video_thumbnail');
            $table->string('model_usdz_url')->nullable()->after('model_glb_url');
            $table->string('model_status')->nullable()->after('model_usdz_url'); // pending, processing, ready, failed
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn(['model_glb_url', 'model_usdz_url', 'model_status']);
        });
    }
};
