<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use App\Notifications\LowBookStockNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class LowBookStockNotificationTest extends TestCase
{
    use DatabaseMigrations;

    public function test_observer_queues_low_stock_email_for_every_administrator(): void
    {
        Notification::fake();
        $administrators = User::factory()->count(2)->create(['role' => 'admin']);
        User::factory()->create(['role' => 'user']);
        $book = Book::factory()->create(['title' => 'หนังสือใกล้หมด', 'stock' => 3]);

        $book->update(['stock' => 2]);

        Notification::assertSentTo(
            $administrators,
            LowBookStockNotification::class,
            function (LowBookStockNotification $notification): bool {
                return $notification instanceof ShouldQueue
                    && $notification->book->title === 'หนังสือใกล้หมด';
            },
        );
    }

    public function test_observer_does_not_repeat_notification_while_stock_remains_below_threshold(): void
    {
        Notification::fake();
        User::factory()->create(['role' => 'admin']);
        $book = Book::factory()->create(['stock' => 2]);

        $book->update(['stock' => 1]);

        Notification::assertNothingSent();
    }

    public function test_borrowing_a_book_queues_the_low_stock_email(): void
    {
        Notification::fake();
        $administrator = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['role' => 'user']);
        $book = Book::factory()->create(['stock' => 3]);

        $this->actingAs($member)
            ->post(route('borrows.store'), ['book_id' => $book->id])
            ->assertRedirect(route('borrows.index'));

        Notification::assertSentTo($administrator, LowBookStockNotification::class);
        $this->assertSame(2, $book->fresh()->stock);
    }
}
