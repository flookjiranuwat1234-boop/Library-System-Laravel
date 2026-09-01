<?php

namespace App\Notifications;

use App\Models\BorrowRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewBorrowRequestNotification extends Notification
{
    use Queueable;

    public function __construct(public BorrowRecord $borrowRecord) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'borrow_request',
            'title' => 'มีคำขอยืมหนังสือใหม่',
            'message' => "{$this->borrowRecord->user->name} ขอยืมหนังสือ “{$this->borrowRecord->book->title}”",
            'borrow_record_id' => $this->borrowRecord->id,
            'book_id' => $this->borrowRecord->book_id,
            'user_id' => $this->borrowRecord->user_id,
            'url' => route('borrows.index'),
        ];
    }
}
