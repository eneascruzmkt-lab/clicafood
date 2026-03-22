<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MenuItemController extends Controller
{
    public function index(Request $request)
    {
        $restaurant = $request->user()->restaurant;

        $items = $restaurant->menuItems()
            ->with('category:id,name')
            ->orderBy('order')
            ->get();

        return Inertia::render('Menu/Index', [
            'items' => $items,
        ]);
    }

    public function create(Request $request)
    {
        $categories = $request->user()->restaurant->categories()
            ->where('active', true)
            ->orderBy('order')
            ->get(['id', 'name']);

        return Inertia::render('Menu/Form', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:4096',
            'video' => 'nullable|mimes:mp4,webm,mov|max:51200',
            'video_url_external' => 'nullable|url',
            'featured' => 'boolean',
            'available' => 'boolean',
        ]);

        $restaurant = $request->user()->restaurant;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('menu-items', 'public');
        }

        if ($request->hasFile('video')) {
            $validated['video_url'] = $request->file('video')->store('videos', 'public');
        } elseif ($request->filled('video_url_external')) {
            $validated['video_url'] = $request->video_url_external;
        }

        unset($validated['video'], $validated['video_url_external']);

        $validated['restaurant_id'] = $restaurant->id;
        $validated['order'] = $restaurant->menuItems()->max('order') + 1;

        MenuItem::create($validated);

        return redirect()->route('menu-items.index')->with('success', 'Item adicionado ao cardápio!');
    }

    public function edit(Request $request, MenuItem $menuItem)
    {
        $this->authorize($menuItem);

        $categories = $request->user()->restaurant->categories()
            ->where('active', true)
            ->orderBy('order')
            ->get(['id', 'name']);

        return Inertia::render('Menu/Form', [
            'item' => $menuItem,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $this->authorize($menuItem);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:4096',
            'video' => 'nullable|mimes:mp4,webm,mov|max:51200',
            'video_url_external' => 'nullable|url',
            'featured' => 'boolean',
            'available' => 'boolean',
            'remove_video' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($menuItem->image) {
                Storage::disk('public')->delete($menuItem->image);
            }
            $validated['image'] = $request->file('image')->store('menu-items', 'public');
        }

        if ($request->boolean('remove_video') && $menuItem->video_url) {
            if (!str_starts_with($menuItem->video_url, 'http')) {
                Storage::disk('public')->delete($menuItem->video_url);
            }
            $validated['video_url'] = null;
            $validated['video_thumbnail'] = null;
        } elseif ($request->hasFile('video')) {
            if ($menuItem->video_url && !str_starts_with($menuItem->video_url, 'http')) {
                Storage::disk('public')->delete($menuItem->video_url);
            }
            $validated['video_url'] = $request->file('video')->store('videos', 'public');
        } elseif ($request->filled('video_url_external')) {
            $validated['video_url'] = $request->video_url_external;
        }

        unset($validated['video'], $validated['video_url_external'], $validated['remove_video']);

        $menuItem->update($validated);

        return redirect()->route('menu-items.index')->with('success', 'Item atualizado!');
    }

    public function destroy(MenuItem $menuItem)
    {
        $this->authorize($menuItem);

        if ($menuItem->image) {
            Storage::disk('public')->delete($menuItem->image);
        }
        if ($menuItem->video_url && !str_starts_with($menuItem->video_url, 'http')) {
            Storage::disk('public')->delete($menuItem->video_url);
        }

        $menuItem->delete();

        return redirect()->route('menu-items.index')->with('success', 'Item removido!');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:menu_items,id',
        ]);

        $restaurant = $request->user()->restaurant;

        foreach ($request->ids as $index => $id) {
            $restaurant->menuItems()->where('id', $id)->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    private function authorize(MenuItem $menuItem): void
    {
        if ($menuItem->restaurant_id !== request()->user()->restaurant->id) {
            abort(403);
        }
    }
}
