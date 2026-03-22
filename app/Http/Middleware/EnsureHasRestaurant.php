<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasRestaurant
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Admin users should use /admin routes
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Owner without restaurant — let them create one via settings
        if (!$user->restaurant && !$request->routeIs('settings.*') && !$request->routeIs('logout')) {
            return redirect()->route('settings.edit');
        }

        return $next($request);
    }
}
