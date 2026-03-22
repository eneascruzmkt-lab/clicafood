<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $restaurant = $request->user()->restaurant;
        $period = $request->get('period', 'month');

        $viewsByDay = $restaurant->analytics()
            ->views()
            ->period($period)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topItems = $restaurant->menuItems()
            ->orderByDesc('views_count')
            ->limit(10)
            ->get(['id', 'name', 'views_count', 'video_plays_count']);

        $topVideos = $restaurant->menuItems()
            ->whereNotNull('video_url')
            ->orderByDesc('video_plays_count')
            ->limit(10)
            ->get(['id', 'name', 'video_plays_count']);

        $totalViews = $restaurant->analytics()->views()->period($period)->count();
        $totalClicks = $restaurant->analytics()->clicks()->period($period)->count();
        $totalVideoPlays = $restaurant->analytics()->videoPlays()->period($period)->count();

        return Inertia::render('Analytics/Index', [
            'viewsByDay' => $viewsByDay,
            'topItems' => $topItems,
            'topVideos' => $topVideos,
            'stats' => [
                'totalViews' => $totalViews,
                'totalClicks' => $totalClicks,
                'totalVideoPlays' => $totalVideoPlays,
            ],
            'period' => $period,
        ]);
    }
}
