<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        $admin = User::create([
            'name' => 'Admin ClicaFood',
            'email' => 'admin@clicafood.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Demo restaurant
        $owner = User::create([
            'name' => 'João Demo',
            'email' => 'demo@clicafood.com',
            'phone' => '11999999999',
            'password' => Hash::make('demo1234'),
            'role' => 'owner',
        ]);

        $restaurant = Restaurant::create([
            'user_id' => $owner->id,
            'name' => 'Burger Station',
            'slug' => 'burger-station',
            'description' => 'Os melhores hambúrgueres artesanais da cidade',
            'primary_color' => '#E63B2E',
            'secondary_color' => '#0d0d0d',
            'whatsapp' => '5511999999999',
            'instagram' => '@burgerstation',
            'address' => 'Rua dos Sabores, 123 - São Paulo',
        ]);

        // Categories
        $burgers = Category::create([
            'restaurant_id' => $restaurant->id,
            'name' => 'Hambúrgueres',
            'order' => 0,
            'active' => true,
        ]);

        $sides = Category::create([
            'restaurant_id' => $restaurant->id,
            'name' => 'Acompanhamentos',
            'order' => 1,
            'active' => true,
        ]);

        $drinks = Category::create([
            'restaurant_id' => $restaurant->id,
            'name' => 'Bebidas',
            'order' => 2,
            'active' => true,
        ]);

        // Menu items
        $items = [
            ['name' => 'X-Burger Clássico', 'description' => 'Pão brioche, blend 180g, queijo cheddar, alface, tomate e molho especial', 'price' => 32.90, 'category_id' => $burgers->id, 'featured' => true],
            ['name' => 'Smash Burger Duplo', 'description' => 'Dois smash patties 90g, queijo americano, cebola caramelizada, pickles', 'price' => 38.90, 'category_id' => $burgers->id, 'featured' => true],
            ['name' => 'Chicken Burger', 'description' => 'Filé de frango empanado, coleslaw, maionese de ervas', 'price' => 29.90, 'category_id' => $burgers->id, 'featured' => false],
            ['name' => 'Veggie Burger', 'description' => 'Hambúrguer de grão de bico, rúcula, tomate seco, maionese vegana', 'price' => 34.90, 'category_id' => $burgers->id, 'featured' => false],
            ['name' => 'Batata Frita', 'description' => 'Porção generosa de batatas fritas crocantes', 'price' => 18.90, 'category_id' => $sides->id, 'featured' => false],
            ['name' => 'Onion Rings', 'description' => 'Anéis de cebola empanados com molho BBQ', 'price' => 22.90, 'category_id' => $sides->id, 'featured' => true],
            ['name' => 'Coca-Cola', 'description' => 'Lata 350ml', 'price' => 7.90, 'category_id' => $drinks->id, 'featured' => false],
            ['name' => 'Milkshake Ovomaltine', 'description' => 'Milkshake cremoso de ovomaltine 400ml', 'price' => 19.90, 'category_id' => $drinks->id, 'featured' => true],
        ];

        foreach ($items as $i => $item) {
            MenuItem::create([
                'restaurant_id' => $restaurant->id,
                'category_id' => $item['category_id'],
                'name' => $item['name'],
                'description' => $item['description'],
                'price' => $item['price'],
                'featured' => $item['featured'],
                'available' => true,
                'order' => $i,
            ]);
        }
    }
}
