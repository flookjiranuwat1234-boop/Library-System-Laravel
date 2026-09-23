<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $categoryId = $request->integer('category');
        $publisher = $request->string('publisher')->trim()->toString();
        $year = $request->integer('year');

        $books = Book::query()
            ->with('category')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('author', 'like', "%{$search}%");
                });
            })
            ->when($categoryId > 0, fn ($query) => $query->where('category_id', $categoryId))
            ->when($publisher !== '', fn ($query) => $query->where('publisher', 'like', "%{$publisher}%"))
            ->when($year > 0, fn ($query) => $query->where('year', $year))
            ->orderBy('title')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::query()->orderBy('name')->get();

        return view('catalog.index', compact('books', 'categories', 'search', 'categoryId', 'publisher', 'year'));
    }
}
