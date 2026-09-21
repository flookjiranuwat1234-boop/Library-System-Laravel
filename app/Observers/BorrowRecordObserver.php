<?php

namespace App\Observers;

use App\Models\BorrowRecord;
use App\Services\ReadingJourneyService;
use Illuminate\Support\Facades\Log;
use Throwable;

class BorrowRecordObserver
{
    public function __construct(
        public ReadingJourneyService $journeyService
    ) {}

    /**
     * Handle the BorrowRecord "updated" event.
     */
    public function updated(BorrowRecord $record): void
    {
        if (! $record->wasChanged('status') || $record->status !== 'returned') {
            return;
        }

        try {
            $user = $record->user;
            if ($user !== null) {
                $this->journeyService->recalculateFor($user);
                $this->journeyService->syncBadgesFor($user);
            }
        } catch (Throwable $e) {
            Log::error('Reading Journey calculation failed on borrow return: '.$e->getMessage(), [
                'borrow_id' => $record->id,
                'user_id' => $record->user_id,
                'exception' => $e,
            ]);
        }
    }
}
