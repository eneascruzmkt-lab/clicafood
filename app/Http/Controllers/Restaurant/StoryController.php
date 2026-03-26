<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class StoryController extends Controller
{
    public function index(Request $request)
    {
        $stories = $request->user()->restaurant->stories()
            ->orderBy('order')
            ->get();

        return Inertia::render('Stories/Index', [
            'stories' => $stories,
        ]);
    }

    public function create()
    {
        return Inertia::render('Stories/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'required|image|max:4096',
            'active' => 'boolean',
        ]);

        $restaurant = $request->user()->restaurant;

        $validated['image'] = $request->file('image')->store('stories', 's3');
        $validated['restaurant_id'] = $restaurant->id;
        $validated['order'] = $restaurant->stories()->max('order') + 1;

        Story::create($validated);

        return redirect()->route('stories.index')->with('success', 'Story adicionado!');
    }

    public function edit(Story $story)
    {
        $this->authorize($story);

        return Inertia::render('Stories/Form', [
            'story' => $story,
        ]);
    }

    public function update(Request $request, Story $story)
    {
        $this->authorize($story);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:4096',
            'active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($story->image && !str_starts_with($story->image, 'http')) {
                Storage::disk('s3')->delete($story->image);
            }
            $validated['image'] = $request->file('image')->store('stories', 's3');
        }

        $story->update($validated);

        return redirect()->route('stories.index')->with('success', 'Story atualizado!');
    }

    public function destroy(Story $story)
    {
        $this->authorize($story);

        if ($story->image && !str_starts_with($story->image, 'http')) {
            Storage::disk('s3')->delete($story->image);
        }

        $story->delete();

        return redirect()->route('stories.index')->with('success', 'Story removido!');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:stories,id',
        ]);

        $restaurant = $request->user()->restaurant;

        foreach ($request->ids as $index => $id) {
            $restaurant->stories()->where('id', $id)->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    private function authorize(Story $story): void
    {
        if ($story->restaurant_id !== request()->user()->restaurant->id) {
            abort(403);
        }
    }
}
