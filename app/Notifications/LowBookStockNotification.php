<?php

namespace App\Notifications;

use App\Models\Book;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowBookStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /** @var array<int, int> */
    public array $backoff = [10, 30, 60];

    public function __construct(public Book $book) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('แจ้งเตือนหนังสือใกล้หมด: '.$this->book->title)
            ->greeting('แจ้งเตือนสต็อกหนังสือ')
            ->line("หนังสือ “{$this->book->title}” เหลือ {$this->book->stock} เล่ม")
            ->line('กรุณาตรวจสอบและเติมจำนวนหนังสือในระบบ')
            ->action('เปิดหน้าหนังสือ', route('books.show', $this->book));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'low_stock',
            'title' => 'หนังสือใกล้หมด',
            'message' => "{$this->book->title} เหลือ {$this->book->stock} เล่ม",
            'book_id' => $this->book->id,
        ];
    }
}
