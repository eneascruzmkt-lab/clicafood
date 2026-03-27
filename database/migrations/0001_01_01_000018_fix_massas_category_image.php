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
            DB::table('categories')
                ->where('restaurant_id', $rid)
                ->where('name', 'Massas Monte a Sua')
                ->update(['image' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/menu-items/GO3iKMzrtXiJmcpoUAR3RZQLFmnvDC6WvCax3NAP.jpg']);
        }
    }

    public function down(): void {}
};
