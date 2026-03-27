<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect('/login');
        }

        // Admins bypass subscription check
        if ($user->isAdmin()) {
            return $next($request);
        }

        $status = $user->subscription_status;

        // Allow active and trial users
        if (in_array($status, ['active', 'trial'])) {
            return $next($request);
        }

        // Pending, canceled, or null — redirect to plans page
        return redirect()->route('plans');
    }
}
