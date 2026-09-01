<?php

namespace App\Observers;

use App\Models\Book;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\LowBookStockNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class BookObserver
{
    public function updated(Book $book): void
    {
        $threshold = (int) Setting::valueFor('low_stock_threshold', config('library.low_stock_threshold'));

        if (! $book->wasChanged('stock') || $book->stock > $threshold || $book->getOriginal('stock') <= $threshold) {
            return;
        }

        Notification::send(
            User::query()->where('role', 'admin')->get(),
            (new LowBookStockNotification($book))->afterCommit(),
        );
    }

    /**
     * Handle the Book "deleted" event.
     */
    public function deleted(Book $book): void
    {
        if ($book->cover_image !== null && ! str_contains($book->cover_image, '://')) {
            Storage::disk('public')->delete($book->cover_image);
        }
    }
}
