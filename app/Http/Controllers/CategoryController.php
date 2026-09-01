<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()->withCount('books')->orderBy('name')->get();

        return view('categories.index', compact('categories'));
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

        return redirect()->route('categories.index')->with('success', 'เพิ่มหมวดหมู่เรียบร้อยแล้ว');
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

        return redirect()->route('categories.index')->with('success', 'แก้ไขหมวดหมู่เรียบร้อยแล้ว');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->books()->exists()) {
            return back()->with('error', 'ไม่สามารถลบหมวดหมู่ที่ยังมีหนังสืออยู่ได้');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'ลบหมวดหมู่เรียบร้อยแล้ว');
    }
}
