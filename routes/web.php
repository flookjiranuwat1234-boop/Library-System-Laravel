<?php

use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowRecordController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AdminMiddleware;
use App\Models\Book;
use App\Models\BorrowRecord;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('welcome', [
        'totalBooks' => Book::count(),
        'totalCategories' => Category::count(),
        'activeLoans' => BorrowRecord::whereIn('status', ['borrowed', 'overdue'])->count(),
        'featuredBooks' => Book::with('category')->latest()->take(3)->get(),
    ]);
});

Route::get('/dashboard', function () {
    $user = request()->user();

    return view('dashboard', [
        'totalBooks' => Book::count(),
        'availableBooks' => Book::where('stock', '>', 0)->count(),
        'activeBorrowings' => BorrowRecord::query()
            ->whereBelongsTo($user)
            ->whereIn('status', ['borrowed', 'overdue'])
            ->count(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [BookController::class, 'show'])->whereNumber('book')->name('books.show');
    Route::get('/borrows', [BorrowRecordController::class, 'index'])->name('borrows.index');
    Route::post('/borrows', [BorrowRecordController::class, 'store'])->name('borrows.store');

    // Admin Routes
    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('books', BookController::class)->except(['index', 'show']);
        Route::post('/borrows/{borrow}/return', [BorrowRecordController::class, 'returnBook'])->name('borrows.return');
        Route::get('/admin/settings', [SettingsController::class, 'edit'])->name('admin.settings.edit');
        Route::put('/admin/settings', [SettingsController::class, 'update'])->name('admin.settings.update');
        Route::post('/admin/settings/test-email', [SettingsController::class, 'testEmail'])->name('admin.settings.test-email');
        Route::get('/admin/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');
        Route::post('/admin/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('admin.notifications.read-all');
        Route::get('/admin/reports', [ReportController::class, 'index'])->name('admin.reports.index');
        Route::get('/admin/reports/print', [ReportController::class, 'print'])->name('admin.reports.print');
        Route::get('/admin/reports/csv', [ReportController::class, 'csv'])->name('admin.reports.csv');
    });
});

require __DIR__.'/auth.php';
