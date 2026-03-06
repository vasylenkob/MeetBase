<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('events')->orderBy('name')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'icon' => 'nullable|string|max:10',
        ]);
        $data['slug'] = Str::slug($data['name']);

        Category::create($data);

        return back()->with('success', 'Категорію додано.');
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'icon' => 'nullable|string|max:10',
        ]);
        $data['slug'] = Str::slug($data['name']);

        $category->update($data);

        return back()->with('success', 'Категорію оновлено.');
    }

    public function destroy(Category $category)
    {
        if ($category->events()->exists()) {
            return back()->with('error', 'Неможливо видалити категорію з заходами.');
        }

        $category->delete();
        return back()->with('success', 'Категорію видалено.');
    }
}
