<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function edit(Request $request)
    {
        $restaurant = $request->user()->restaurant;

        return Inertia::render('Settings/Edit', [
            'restaurant' => array_merge($restaurant->toArray(), [
                'logo_url' => $restaurant->logo ? Storage::disk('s3')->url($restaurant->logo) : null,
            ]),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|max:2048',
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'text_color' => 'nullable|string|max:7',
            'text_secondary_color' => 'nullable|string|max:7',
            'border_color' => 'nullable|string|max:7',
            'price_color' => 'nullable|string|max:7',
            'font_family' => 'nullable|string|max:50',
            'background_image' => 'nullable|image|max:4096',
            'background_opacity' => 'nullable|integer|min:0|max:100',
            'menu_layout' => 'nullable|string|in:reels,categories,categories_featured',
            'remove_background' => 'nullable|boolean',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'working_hours' => 'nullable|array',
        ]);

        $restaurant = $request->user()->restaurant;

        if ($request->hasFile('logo')) {
            if ($restaurant->logo) {
                Storage::disk('s3')->delete($restaurant->logo);
            }
            $validated['logo'] = $request->file('logo')->store('logos', 's3');
        }

        if ($request->hasFile('background_image')) {
            if ($restaurant->background_image && !str_starts_with($restaurant->background_image, 'http')) {
                Storage::disk('s3')->delete($restaurant->background_image);
            }
            $validated['background_image'] = $request->file('background_image')->store('backgrounds', 's3');
        } elseif ($request->boolean('remove_background')) {
            if ($restaurant->background_image && !str_starts_with($restaurant->background_image, 'http')) {
                Storage::disk('s3')->delete($restaurant->background_image);
            }
            $validated['background_image'] = null;
        }

        unset($validated['remove_background']);

        $restaurant->update($validated);

        return redirect()->route('settings.edit')->with('success', 'Configurações salvas!');
    }
}
