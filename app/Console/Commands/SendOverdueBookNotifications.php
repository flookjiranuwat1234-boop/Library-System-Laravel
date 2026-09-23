<?php

namespace App\Console\Commands;

use App\Models\BorrowRecord;
use App\Notifications\OverdueBookNotification;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

class SendOverdueBookNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'library:notify-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queue notifications for newly overdue library books';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $notified = 0;

        BorrowRecord::query()
            ->with(['book', 'user'])
            ->whereIn('status', ['borrowed', 'overdue'])
            ->whereDate('due_date', '<', today())
            ->whereNull('overdue_notified_at')
            ->lazyById()
            ->each(function (BorrowRecord $borrowRecord) use (&$notified): void {
                $borrowRecord->update([
                    'status' => 'overdue',
                    'overdue_notified_at' => now(),
                ]);
                $borrowRecord->user->notify((new OverdueBookNotification($borrowRecord))->afterCommit());
                $notified++;
            });

        $this->info("จัดคิวแจ้งเตือน {$notified} รายการ");

        return self::SUCCESS;
    }
}
