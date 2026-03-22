<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Analytic;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $restaurant = $request->user()->restaurant;

        if (!$restaurant) {
            return redirect()->route('settings.edit');
        }

        $totalViews = $restaurant->analytics()->views()->count();
        $totalClicks = $restaurant->analytics()->clicks()->count();
        $totalVideoPlays = $restaurant->analytics()->videoPlays()->count();
        $totalItems = $restaurant->menuItems()->count();

        $topItems = $restaurant->menuItems()
            ->orderByDesc('views_count')
            ->limit(5)
            ->get(['id', 'name', 'views_count', 'video_plays_count', 'image']);

        $topVideos = $restaurant->menuItems()
            ->whereNotNull('video_url')
            ->orderByDesc('video_plays_count')
            ->limit(5)
            ->get(['id', 'name', 'video_plays_count', 'video_thumbnail']);

        $recentViews = $restaurant->analytics()
            ->views()
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return Inertia::render('Dashboard/Index', [
            'stats' => [
                'totalViews' => $totalViews,
                'totalClicks' => $totalClicks,
                'totalVideoPlays' => $totalVideoPlays,
                'totalItems' => $totalItems,
            ],
            'topItems' => $topItems,
            'topVideos' => $topVideos,
            'recentViews' => $recentViews,
            'restaurant' => $restaurant,
        ]);
    }
}
