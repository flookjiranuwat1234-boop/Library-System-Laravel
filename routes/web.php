<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\BadgeAdminController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ReadingJourneyAdminController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowRecordController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReadingJourneyController;
use App\Http\Middleware\AdminMiddleware;
use App\Models\Book;
use App\Models\BorrowRecord;
use App\Models\Category;
use App\Services\ReadingJourneyService;
use Illuminate\Support\Facades\Route;

// Public routes
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

Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');

Route::get('/dashboard', function () {
    $user = request()->user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->readingStat === null) {
        app(ReadingJourneyService::class)->recalculateFor($user);
        app(ReadingJourneyService::class)->syncBadgesFor($user);
        $user->unsetRelation('readingStat');
        $user->unsetRelation('badges');
    }

    $user->loadMissing(['readingStat', 'badges']);

    return view('dashboard', [
        'totalBooks' => Book::count(),
        'availableBooks' => Book::where('stock', '>', 0)->count(),
        'activeBorrowings' => BorrowRecord::query()
            ->whereBelongsTo($user)
            ->whereIn('status', ['borrowed', 'overdue'])
            ->count(),
        'latestBooks' => Book::query()->with('category')->latest()->take(4)->get(),
        'dueSoonBorrows' => BorrowRecord::query()
            ->with('book')
            ->whereBelongsTo($user)
            ->whereIn('status', ['borrowed', 'overdue'])
            ->orderBy('due_date')
            ->take(4)
            ->get(),
        'readingStat' => $user->readingStat,
        'recentBadges' => $user->badges()->latest('user_badges.unlocked_at')->take(3)->get(),
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
    Route::post('/borrows/{borrow}/renew', [BorrowRecordController::class, 'renew'])->name('borrows.renew');
    Route::get('/journey', [ReadingJourneyController::class, 'index'])->name('journey.index');

    // Admin Routes
    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('books', BookController::class)->except(['index', 'show']);
        Route::get('/books/{book}/qr', [BookController::class, 'qr'])->name('books.qr');
        Route::post('/borrows/{borrow}/return', [BorrowRecordController::class, 'returnBook'])->name('borrows.return');
        Route::post('/borrows/{borrow}/approve', [BorrowRecordController::class, 'approve'])->name('borrows.approve');
        Route::post('/borrows/{borrow}/reject', [BorrowRecordController::class, 'reject'])->name('borrows.reject');
        Route::get('/admin/settings', [SettingsController::class, 'edit'])->name('admin.settings.edit');
        Route::put('/admin/settings', [SettingsController::class, 'update'])->name('admin.settings.update');
        Route::post('/admin/settings/test-email', [SettingsController::class, 'testEmail'])->name('admin.settings.test-email');
        Route::get('/admin/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');
        Route::post('/admin/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('admin.notifications.read-all');
        Route::get('/admin/reports', [ReportController::class, 'index'])->name('admin.reports.index');
        Route::get('/admin/reports/print', [ReportController::class, 'print'])->name('admin.reports.print');
        Route::get('/admin/reports/csv', [ReportController::class, 'csv'])->name('admin.reports.csv');
        Route::get('/admin/activity-logs', [ActivityLogController::class, 'index'])->name('admin.activity-logs.index');

        // Reading Journey & Badges Management
        Route::get('/admin/journey', [ReadingJourneyAdminController::class, 'index'])->name('admin.journey.index');
        Route::get('/admin/journey/{user}', [ReadingJourneyAdminController::class, 'show'])->name('admin.journey.show');
        Route::post('/admin/journey/recalculate-all', [ReadingJourneyAdminController::class, 'recalculateAll'])->name('admin.journey.recalculate-all');
        Route::post('/admin/journey/{user}/recalculate', [ReadingJourneyAdminController::class, 'recalculate'])->name('admin.journey.recalculate');
        Route::post('/admin/journey/{user}/award-badge', [ReadingJourneyAdminController::class, 'awardBadge'])->name('admin.journey.award-badge');
        Route::delete('/admin/journey/{user}/revoke-badge/{badge}', [ReadingJourneyAdminController::class, 'revokeBadge'])->name('admin.journey.revoke-badge');
        Route::post('/admin/journey/{user}/adjust-points', [ReadingJourneyAdminController::class, 'adjustPoints'])->name('admin.journey.adjust-points');

        Route::resource('admin/badges', BadgeAdminController::class, [
            'as' => 'admin',
        ]);
    });
});

require __DIR__.'/auth.php';
