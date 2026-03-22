<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('primary_color', 7)->default('#E63B2E');
            $table->string('secondary_color', 7)->default('#1a1a1a');
            $table->string('address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('instagram')->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->json('working_hours')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
