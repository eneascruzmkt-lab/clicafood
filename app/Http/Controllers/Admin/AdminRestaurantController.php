<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminRestaurantController extends Controller
{
    public function index(Request $request)
    {
        $restaurants = Restaurant::with('user:id,name,email')
            ->withCount(['menuItems', 'categories'])
            ->when($request->search, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($q) => $q->where('email', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(20);

        return Inertia::render('Admin/Restaurants', [
            'restaurants' => $restaurants,
            'search' => $request->search,
        ]);
    }

    public function show(Restaurant $restaurant)
    {
        $restaurant->load(['user', 'categories', 'menuItems']);
        $restaurant->loadCount(['menuItems', 'categories', 'analytics']);

        return Inertia::render('Admin/RestaurantDetail', [
            'restaurant' => $restaurant,
        ]);
    }

    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();
        return redirect()->route('admin.restaurants.index')->with('success', 'Restaurante removido.');
    }
}
