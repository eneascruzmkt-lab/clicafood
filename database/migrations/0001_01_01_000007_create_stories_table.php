<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->string('image');
            $table->unsignedInteger('order')->default(0);
            $table->boolean('active')->default(true);
            $table->unsignedInteger('views_count')->default(0);
            $table->timestamps();

            $table->index(['restaurant_id', 'active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};
