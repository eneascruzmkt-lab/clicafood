<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Analytic;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MenuController extends Controller
{
    public function show(string $slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();

        $categories = $restaurant->categories()
            ->where('active', true)
            ->orderBy('order')
            ->get(['id', 'name', 'image']);

        $items = $restaurant->menuItems()
            ->where('available', true)
            ->with('category:id,name')
            ->orderBy('order')
            ->get([
                'id', 'category_id', 'name', 'description', 'price',
                'image', 'video_url', 'video_thumbnail', 'featured', 'order',
            ]);

        // Track page view
        Analytic::create([
            'restaurant_id' => $restaurant->id,
            'event_type' => 'view',
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // URLs are resolved automatically by model toArray()
        $restaurantData = $restaurant->toArray();
        $restaurantData = array_intersect_key($restaurantData, array_flip([
            'id', 'name', 'slug', 'description', 'logo', 'primary_color', 'secondary_color',
            'phone', 'instagram', 'whatsapp', 'address', 'working_hours',
        ]));

        // Stories: combine dedicated stories + featured menu items
        $stories = $restaurant->stories()
            ->where('active', true)
            ->orderBy('order')
            ->get()
            ->map(fn ($s) => ['id' => 'story-'.$s->id, 'title' => $s->title, 'image' => $s->image, 'type' => 'story']);

        $featuredStories = $items->where('featured', true)->values()
            ->map(fn ($item) => ['id' => 'item-'.$item->id, 'title' => $item->name, 'image' => $item->image ?? $item->video_thumbnail, 'type' => 'item', 'item_id' => $item->id]);

        $allStories = $stories->concat($featuredStories)->values();

        return Inertia::render('Public/Menu', [
            'restaurant' => $restaurantData,
            'categories' => $categories,
            'items' => $items,
            'stories' => $allStories,
        ]);
    }

    public function track(Request $request, string $slug)
    {
        $request->validate([
            'event_type' => 'required|in:click,video_play',
            'menu_item_id' => 'required|exists:menu_items,id',
        ]);

        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();

        Analytic::create([
            'restaurant_id' => $restaurant->id,
            'menu_item_id' => $request->menu_item_id,
            'event_type' => $request->event_type,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Update counters
        $item = MenuItem::find($request->menu_item_id);
        if ($request->event_type === 'click') {
            $item->increment('views_count');
        } elseif ($request->event_type === 'video_play') {
            $item->increment('video_plays_count');
        }

        return response()->json(['ok' => true]);
    }
}
