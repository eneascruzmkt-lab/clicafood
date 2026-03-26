<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('text_color', 7)->default('#ffffff')->after('secondary_color');
            $table->string('text_secondary_color', 7)->default('#9ca3af')->after('text_color');
            $table->string('border_color', 7)->default('#333333')->after('text_secondary_color');
            $table->string('price_color', 7)->nullable()->after('border_color');
            $table->string('font_family')->default('Inter')->after('price_color');
            $table->string('background_image')->nullable()->after('font_family');
            $table->unsignedTinyInteger('background_opacity')->default(100)->after('background_image');
        });
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn([
                'text_color', 'text_secondary_color', 'border_color',
                'price_color', 'font_family', 'background_image', 'background_opacity',
            ]);
        });
    }
};
