<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $slugs = ['nonna-reels', 'nonna-categorias', 'nonna-stories'];
        $restaurantIds = DB::table('restaurants')->whereIn('slug', $slugs)->pluck('id');

        foreach ($restaurantIds as $rid) {
            // Delete "Promoção" story
            DB::table('stories')
                ->where('restaurant_id', $rid)
                ->where('title', 'Promoção')
                ->delete();

            // Get Bebidas category id
            $bebidasCatId = DB::table('categories')
                ->where('restaurant_id', $rid)
                ->where('name', 'Bebidas')
                ->value('id');

            // Delete menu items without video, EXCEPT those in Bebidas category
            DB::table('menu_items')
                ->where('restaurant_id', $rid)
                ->whereNull('video_url')
                ->where(function ($q) use ($bebidasCatId) {
                    if ($bebidasCatId) {
                        $q->where('category_id', '!=', $bebidasCatId);
                    }
                })
                ->delete();
        }
    }

    public function down(): void {}
};
