<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowRecord;
use App\Models\Category;
use App\Models\Setting;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $totalBooks = Book::count();
        $totalMembers = User::where('role', 'user')->count();
        $activeLoans = BorrowRecord::whereIn('status', ['borrowed', 'overdue'])->count();
        $overdueLoans = BorrowRecord::whereIn('status', ['borrowed', 'overdue'])
            ->whereDate('due_date', '<', today())
            ->count();
        $availableBooks = Book::where('stock', '>', 0)->count();
        $totalCategories = Category::count();

        $recentBorrows = BorrowRecord::with(['user', 'book'])
            ->whereIn('status', ['borrowed', 'overdue'])
            ->latest('borrowed_at')
            ->take(5)
            ->get();

        $recentReturns = BorrowRecord::with(['user', 'book'])
            ->where('status', 'returned')
            ->latest('returned_at')
            ->take(5)
            ->get();

        $overdueBooks = BorrowRecord::with(['user', 'book'])
            ->whereIn('status', ['borrowed', 'overdue'])
            ->whereDate('due_date', '<', today())
            ->orderBy('due_date')
            ->get();

        $lowStockThreshold = (int) Setting::valueFor('low_stock_threshold', config('library.low_stock_threshold'));
        $lowStockBooks = Book::where('stock', '>', 0)
            ->where('stock', '<=', $lowStockThreshold)
            ->get();

        // Top categories with book counts
        $topCategories = Category::withCount('books')
            ->orderByDesc('books_count')
            ->take(5)
            ->get();

        // Popular books with highest borrow count
        $popularBooks = Book::withCount('borrowRecords')
            ->with('category')
            ->orderByDesc('borrow_records_count')
            ->take(4)
            ->get();

        // Latest added books
        $latestBooks = Book::with('category')
            ->latest()
            ->take(4)
            ->get();

        return view('admin.dashboard', [
            'totalBooks' => $totalBooks,
            'totalMembers' => $totalMembers,
            'activeLoans' => $activeLoans,
            'overdueLoans' => $overdueLoans,
            'availableBooks' => $availableBooks,
            'totalCategories' => $totalCategories,
            'recentBorrows' => $recentBorrows,
            'recentReturns' => $recentReturns,
            'overdueBooks' => $overdueBooks,
            'lowStockBooks' => $lowStockBooks,
            'topCategories' => $topCategories,
            'popularBooks' => $popularBooks,
            'latestBooks' => $latestBooks,
        ]);
    }
}
