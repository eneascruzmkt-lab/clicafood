<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Analytic;
use App\Models\Restaurant;
use App\Models\Subscription;
use App\Models\User;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'totalUsers' => User::where('role', 'owner')->count(),
                'totalRestaurants' => Restaurant::count(),
                'totalViews' => Analytic::views()->count(),
                'activePlans' => Subscription::where('status', 'active')->where('plan', 'pro')->count(),
            ],
            'recentRestaurants' => Restaurant::with('user:id,name,email')
                ->latest()
                ->limit(10)
                ->get(['id', 'user_id', 'name', 'slug', 'created_at']),
        ]);
    }
}
