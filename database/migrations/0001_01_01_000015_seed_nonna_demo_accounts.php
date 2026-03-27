<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $layouts = [
            ['email' => 'nonna-reels@clicafood.com', 'slug' => 'nonna-reels', 'layout' => 'reels', 'desc' => 'Layout Reels'],
            ['email' => 'nonna-categorias@clicafood.com', 'slug' => 'nonna-categorias', 'layout' => 'categories', 'desc' => 'Layout Categorias'],
            ['email' => 'nonna-stories@clicafood.com', 'slug' => 'nonna-stories', 'layout' => 'categories_featured', 'desc' => 'Layout Categorias + Stories'],
        ];

        $logo = 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/logos/CAKsNyu73N50tPom8rKu3OfwAp63fmi6bY2b9lAT.png';

        $categories = [
            ['name' => 'Massas Monte a Sua', 'image' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/categories/lEt6BBqc2dpGAp5PnwpUdrJyjW7zvSHHR4RL73Ed.jpg', 'order' => 0],
            ['name' => 'Pratos Especiais', 'image' => null, 'order' => 1],
            ['name' => 'Bebidas', 'image' => null, 'order' => 2],
            ['name' => 'Prato do Dia', 'image' => null, 'order' => 3],
        ];

        $items = [
            // Massas Monte a Sua (cat index 0)
            ['cat' => 0, 'name' => 'Grano Duro - Piccolo', 'desc' => 'Massa tradicional italiana de trigo duro. Porção individual (250g a 350g). Escolha 1 proteína, 4 ingredientes e 1 molho.', 'price' => 24.90, 'featured' => true, 'order' => 0, 'image' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/menu-items/TzLsRKUIiv11fGpyPFm4KDWevOY1j1WC7RlQLf1I.jpg', 'video' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/videos/a60d0462-57f9-4a1e-b224-29a6685946f0.mp4', 'thumb' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/thumbnails/mCIHrtUL5z9JvJ8PBSAHPx05dbJW1C3I1JQ6dxaA.jpg'],
            ['cat' => 0, 'name' => 'Grano Duro - Affamato', 'desc' => 'Massa tradicional italiana de trigo duro. Porção grande (500g a 700g). Escolha 2 proteínas, 6 ingredientes e 2 molhos.', 'price' => 29.90, 'featured' => false, 'order' => 1, 'image' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/menu-items/U6j7ePMeg6KFUJtOKxG0RKnAcH1toNC4eB0prcjz.jpg', 'video' => null, 'thumb' => null],
            ['cat' => 0, 'name' => 'Artesanal - Piccolo', 'desc' => 'Massa artesanal feita à mão diariamente. Porção individual (250g a 350g). Escolha 1 proteína, 4 ingredientes e 1 molho.', 'price' => 29.90, 'featured' => true, 'order' => 2, 'image' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/menu-items/GO3iKMzrtXiJmcpoUAR3RZQLFmnvDC6WvCax3NAP.jpg', 'video' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/videos/556dccb1-8e40-4dc9-84a0-31f5912cce32.mp4', 'thumb' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/thumbnails/gZbj5t2bLouzuZPtoHSPCfydRTtcnfduUqGNz9cT.jpg'],
            ['cat' => 0, 'name' => 'Nero di Sepia - Piccolo', 'desc' => 'Massa negra com tinta de lula. Porção individual (250g a 350g). Escolha 1 proteína, 4 ingredientes e 1 molho.', 'price' => 34.90, 'featured' => true, 'order' => 3, 'image' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/menu-items/JOUKrpvcZ0qQb6oiG5W0e9Yse4uhGaZKB26W3H5l.jpg', 'video' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/videos/58f6b049-b315-4123-b8f5-6d747cca019e.mp4', 'thumb' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/thumbnails/Xsi740k5SeGeTSydRwOQHcZhNon8WHjFHHlkr62F.jpg'],
            ['cat' => 0, 'name' => 'Recheadas - Piccolo', 'desc' => 'Massas recheadas com sabores únicos. Porção individual (250g a 350g). Escolha 1 proteína, 4 ingredientes e 1 molho.', 'price' => 39.90, 'featured' => false, 'order' => 4, 'image' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/menu-items/Lz9Ot5evW7rCLmksr41JL8eEkYIQ65peWrn9X61B.jpg', 'video' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/videos/721163da-f0d8-4094-94fb-8d6494fcd4d7.mp4', 'thumb' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/thumbnails/6WnZuw8K7M2c00UPGS5uADVrJff4GWXxrcsiOqTe.jpg'],
            // Pratos Especiais (cat index 1)
            ['cat' => 1, 'name' => 'Pasta Alla Contadina', 'desc' => 'Massa al dente envolvida em azeite extravirgem com alho, pimentão e tomate fresco salteados. Molho pesto com frango desfiado, criando um sabor rústico e elegante. Finalizada com parmesão ralado.', 'price' => 18.90, 'featured' => true, 'order' => 0, 'image' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/menu-items/vbq5nq6JOLqaHnICvT9XTfmOXpE2PPC54RspCgBx.jpg', 'video' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/videos/7442ec33-f591-4a34-9f03-c671ade2a71f.mp4', 'thumb' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/thumbnails/kTWxTzYfqnHkIca6stHNJRXgxH3bTN9LMpncwLfF.jpg'],
            ['cat' => 1, 'name' => 'Fettuccine Alfredo', 'desc' => 'Fettuccine perfeitamente al dente envolvido em molho clássico de manteiga de alta qualidade e queijo parmesão. Cremosidade sedosa que destaca a pureza dos ingredientes.', 'price' => 17.90, 'featured' => true, 'order' => 1, 'image' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/menu-items/cAnLyFuPnfUoyHFlTvIeE3zMtCfKOJsJWDtiHBIl.jpg', 'video' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/videos/d528ec63-ef5f-470a-b50b-b08a48814248.mp4', 'thumb' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/thumbnails/Q5UzFKj3EwU9D95p0eBfjLcJN2vKWriwLimeBfhe.jpg'],
            ['cat' => 1, 'name' => 'Carbonara', 'desc' => 'Massa al dente com molho cremoso de ovos, queijo parmesão, bacon crocante e pimenta preta. Um clássico italiano irresistível.', 'price' => 19.90, 'featured' => true, 'order' => 2, 'image' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/menu-items/KllA2yFOUGjTe3hfL7025zZ2077isxLpKmHUFLPk.jpg', 'video' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/videos/3ab920cc-73c5-4b1c-8671-dc0e2a3c058b.mp4', 'thumb' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/thumbnails/DwUBQ2Rk83Z31ofUsUDdpJyAS6W0gEcCSWkLTmJj.jpg'],
            // Bebidas (cat index 2)
            ['cat' => 2, 'name' => 'Coca-Cola Original 220ml', 'desc' => 'Lata 220ml', 'price' => 5.90, 'featured' => false, 'order' => 0, 'image' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/menu-items/XRQkD4wxWGNlJ6rC9XJpEQG5DmFURreyxpzuR2GO.jpg', 'video' => null, 'thumb' => null],
            ['cat' => 2, 'name' => 'Água Mineral 510ml', 'desc' => 'Garrafa 510ml', 'price' => 3.00, 'featured' => false, 'order' => 1, 'image' => 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/menu-items/lPzamIjEbzmWMZFoTiV2prZIqFXmIWOw2mPPBNag.jpg', 'video' => null, 'thumb' => null],
            // Prato do Dia (cat index 3)
            ['cat' => 3, 'name' => 'Prato do Dia', 'desc' => 'Consulte o prato especial de hoje. Preparado com ingredientes frescos selecionados.', 'price' => 19.90, 'featured' => false, 'order' => 0, 'image' => null, 'video' => null, 'thumb' => null],
        ];

        $storyImage = 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/stories/3HktdyKojK8HDpRIXPXrIPjLKZMkb206kGiirRog.png';

        foreach ($layouts as $l) {
            // Skip if user already exists
            if (DB::table('users')->where('email', $l['email'])->exists()) {
                continue;
            }

            // Create user
            $userId = DB::table('users')->insertGetId([
                'name' => 'Nonna Titina (' . $l['desc'] . ')',
                'email' => $l['email'],
                'password' => Hash::make('nonna2024'),
                'role' => 'owner',
                'subscription_status' => 'active',
                'subscription_plan' => 'pro',
                'subscription_expires_at' => now()->addYear(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create restaurant
            $restaurantId = DB::table('restaurants')->insertGetId([
                'user_id' => $userId,
                'name' => 'Nonna Titina',
                'slug' => $l['slug'],
                'description' => 'Massa Italiana Artesanal',
                'logo' => $logo,
                'primary_color' => '#E63B2E',
                'secondary_color' => '#000000',
                'text_color' => '#ffffff',
                'text_secondary_color' => '#9ca3af',
                'border_color' => '#333333',
                'price_color' => '#E63B2E',
                'font_family' => 'Inter',
                'background_opacity' => 100,
                'menu_layout' => $l['layout'],
                'show_instagram' => true,
                'show_whatsapp' => false,
                'show_address' => false,
                'instagram' => 'nonnatitina',
                'whatsapp' => '5584996856193',
                'address' => 'fernando bandeira de melo',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create categories
            $catIds = [];
            foreach ($categories as $cat) {
                $catIds[] = DB::table('categories')->insertGetId([
                    'restaurant_id' => $restaurantId,
                    'name' => $cat['name'],
                    'image' => $cat['image'],
                    'order' => $cat['order'],
                    'active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Create menu items
            foreach ($items as $item) {
                DB::table('menu_items')->insert([
                    'restaurant_id' => $restaurantId,
                    'category_id' => $catIds[$item['cat']],
                    'name' => $item['name'],
                    'description' => $item['desc'],
                    'price' => $item['price'],
                    'image' => $item['image'],
                    'video_url' => $item['video'],
                    'video_thumbnail' => $item['thumb'],
                    'featured' => $item['featured'],
                    'available' => true,
                    'order' => $item['order'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Create story
            DB::table('stories')->insert([
                'restaurant_id' => $restaurantId,
                'title' => 'Promoção',
                'image' => $storyImage,
                'order' => 0,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        $emails = ['nonna-reels@clicafood.com', 'nonna-categorias@clicafood.com', 'nonna-stories@clicafood.com'];
        $userIds = DB::table('users')->whereIn('email', $emails)->pluck('id');
        $restaurantIds = DB::table('restaurants')->whereIn('user_id', $userIds)->pluck('id');

        DB::table('stories')->whereIn('restaurant_id', $restaurantIds)->delete();
        DB::table('menu_items')->whereIn('restaurant_id', $restaurantIds)->delete();
        DB::table('categories')->whereIn('restaurant_id', $restaurantIds)->delete();
        DB::table('restaurants')->whereIn('id', $restaurantIds)->delete();
        DB::table('users')->whereIn('id', $userIds)->delete();
    }
};
