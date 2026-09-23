<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('books.index');
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]);

        Category::create($validated);

        return back()->with('success', 'เพิ่มหมวดหมู่เรียบร้อยแล้ว');
    }

    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($category)],
        ]);

        $category->update($validated);

        return back()->with('success', 'แก้ไขหมวดหมู่เรียบร้อยแล้ว');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->books()->update(['category_id' => null]);

        $category->delete();

        return back()->with('success', 'ลบหมวดหมู่เรียบร้อยแล้ว');
    }
}
