<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $restaurant = $request->user()->restaurant;

        $categories = $restaurant->categories()
            ->withCount('menuItems')
            ->orderBy('order')
            ->get();

        return Inertia::render('Categories/Index', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        return Inertia::render('Categories/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'active' => 'boolean',
        ]);

        $restaurant = $request->user()->restaurant;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $validated['restaurant_id'] = $restaurant->id;
        $validated['order'] = $restaurant->categories()->max('order') + 1;

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Categoria criada com sucesso!');
    }

    public function edit(Category $category)
    {
        $this->authorize($category);

        return Inertia::render('Categories/Form', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize($category);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Categoria atualizada!');
    }

    public function destroy(Category $category)
    {
        $this->authorize($category);

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Categoria removida!');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:categories,id',
        ]);

        $restaurant = $request->user()->restaurant;

        foreach ($request->ids as $index => $id) {
            $restaurant->categories()->where('id', $id)->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    private function authorize(Category $category): void
    {
        if ($category->restaurant_id !== request()->user()->restaurant->id) {
            abort(403);
        }
    }
}
