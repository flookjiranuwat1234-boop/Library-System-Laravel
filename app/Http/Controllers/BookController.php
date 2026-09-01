<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $categoryId = $request->integer('category');

        $books = Book::query()
            ->with('category')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('author', 'like', "%{$search}%");
                });
            })
            ->when($categoryId > 0, fn ($query) => $query->where('category_id', $categoryId))
            ->orderBy('title')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()->orderBy('name')->get();

        return view('books.index', compact('books', 'categories', 'search', 'categoryId'));
    }

    public function create(): View
    {
        $categories = Category::all();

        return view('books.create', compact('categories'));
    }

    public function store(StoreBookRequest $request): RedirectResponse
    {
        $validated = $request->safe()->except('cover_image');

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('book-covers', 'public');
        }

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'เพิ่มหนังสือเรียบร้อยแล้ว');
    }

    public function show(Request $request, Book $book): View
    {
        $book->load('category');
        $currentBorrow = $request->user()
            ->borrowRecords()
            ->whereBelongsTo($book)
            ->whereIn('status', ['pending', 'borrowed', 'overdue'])
            ->latest()
            ->first();

        return view('books.show', compact('book', 'currentBorrow'));
    }

    public function edit(Book $book): View
    {
        $categories = Category::all();

        return view('books.edit', compact('book', 'categories'));
    }

    public function update(UpdateBookRequest $request, Book $book): RedirectResponse
    {
        $validated = $request->safe()->except(['cover_image', 'remove_cover']);
        $oldCoverImage = $book->cover_image;

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('book-covers', 'public');
        } elseif ($request->boolean('remove_cover')) {
            $validated['cover_image'] = null;
        }

        $book->update($validated);

        if (array_key_exists('cover_image', $validated) && $oldCoverImage !== $validated['cover_image']) {
            $this->deleteStoredCover($oldCoverImage);
        }

        return redirect()->route('books.index')->with('success', 'แก้ไขข้อมูลหนังสือเรียบร้อยแล้ว');
    }

    public function destroy(Book $book): RedirectResponse
    {
        if ($book->borrowRecords()->exists()) {
            return back()->with('error', 'ไม่สามารถลบหนังสือที่มีประวัติการยืมได้');
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'ลบหนังสือเรียบร้อยแล้ว');
    }

    private function deleteStoredCover(?string $coverImage): void
    {
        if ($coverImage !== null && ! str_contains($coverImage, '://')) {
            Storage::disk('public')->delete($coverImage);
        }
    }
}
