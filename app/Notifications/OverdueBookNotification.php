<?php

namespace App\Notifications;

use App\Models\BorrowRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OverdueBookNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public BorrowRecord $borrowRecord) {}

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
            ->subject('แจ้งเตือนคืนหนังสือเกินกำหนด')
            ->greeting('แจ้งเตือนจากระบบห้องสมุด')
            ->line("หนังสือ “{$this->borrowRecord->book->title}” เกินกำหนดคืนแล้ว")
            ->line('กำหนดคืน: '.$this->borrowRecord->due_date->locale('th')->translatedFormat('j M Y'))
            ->action('ดูรายการยืม', route('borrows.index'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'overdue',
            'title' => 'หนังสือเกินกำหนด',
            'message' => "{$this->borrowRecord->book->title} เกินกำหนดคืน",
            'borrow_record_id' => $this->borrowRecord->id,
        ];
    }
}
