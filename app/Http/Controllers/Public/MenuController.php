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

        // Resolve storage paths to full URLs
        $resolveUrl = fn ($path) => $path ? (str_starts_with($path, 'http') ? $path : Storage::disk('s3')->url($path)) : null;

        $restaurantData = $restaurant->only('id', 'name', 'slug', 'description', 'logo', 'primary_color', 'secondary_color', 'phone', 'instagram', 'whatsapp', 'address', 'working_hours');
        $restaurantData['logo'] = $resolveUrl($restaurant->logo);

        $items->transform(function ($item) use ($resolveUrl) {
            $item->image = $resolveUrl($item->image);
            $item->video_url = $resolveUrl($item->video_url);
            $item->video_thumbnail = $resolveUrl($item->video_thumbnail);
            return $item;
        });

        $categories->transform(function ($cat) use ($resolveUrl) {
            $cat->image = $resolveUrl($cat->image);
            return $cat;
        });

        $featured = $items->where('featured', true)->values();

        return Inertia::render('Public/Menu', [
            'restaurant' => $restaurantData,
            'categories' => $categories,
            'items' => $items,
            'featured' => $featured,
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
