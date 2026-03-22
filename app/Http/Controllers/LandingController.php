<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class LandingController extends Controller
{
    public function index()
    {
        return Inertia::render('Landing/Index', [
            'whatsapp' => [
                'number' => config('services.whatsapp.number'),
                'message' => config('services.whatsapp.message'),
            ],
        ]);
    }
}
