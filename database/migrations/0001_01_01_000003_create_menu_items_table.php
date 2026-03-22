<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('image')->nullable();
            $table->string('video_url')->nullable();
            $table->string('video_thumbnail')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('available')->default(true);
            $table->integer('order')->default(0);
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('video_plays_count')->default(0);
            $table->timestamps();

            $table->index(['restaurant_id', 'category_id']);
            $table->index(['restaurant_id', 'featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
