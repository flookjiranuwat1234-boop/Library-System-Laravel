<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BorrowRecord;
use App\Models\User;
use App\Services\ReadingJourneyService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class JourneyHistorySeeder extends Seeder
{
    /**
     * Seed realistic reading journey history for the test user.
     */
    public function run(ReadingJourneyService $journeyService): void
    {
        $user = User::where('email', 'user@example.com')->first();
        $admin = User::where('email', 'admin@example.com')->first();

        if (! $user) {
            return;
        }

        // Get a diverse collection of books across all categories
        $books = Book::with('category')->get();

        if ($books->count() < 10) {
            return;
        }

        $now = Carbon::now();

        // History specification: [weeks_ago, days_loan_duration, is_on_time, book_index]
        $historyPlan = [
            // Recent weeks (for streak: week 0, 1, 2, 3, 4)
            ['weeksAgo' => 0, 'days' => 6, 'onTime' => true, 'bookIndex' => 0],
            ['weeksAgo' => 1, 'days' => 8, 'onTime' => true, 'bookIndex' => 2],
            ['weeksAgo' => 1, 'days' => 5, 'onTime' => true, 'bookIndex' => 5],
            ['weeksAgo' => 2, 'days' => 7, 'onTime' => true, 'bookIndex' => 8],
            ['weeksAgo' => 3, 'days' => 10, 'onTime' => true, 'bookIndex' => 12],
            ['weeksAgo' => 3, 'days' => 6, 'onTime' => true, 'bookIndex' => 15],
            ['weeksAgo' => 4, 'days' => 9, 'onTime' => true, 'bookIndex' => 18],

            // Earlier months
            ['weeksAgo' => 6, 'days' => 12, 'onTime' => true, 'bookIndex' => 20],
            ['weeksAgo' => 7, 'days' => 14, 'onTime' => true, 'bookIndex' => 22],
            ['weeksAgo' => 8, 'days' => 18, 'onTime' => false, 'bookIndex' => 25], // late
            ['weeksAgo' => 10, 'days' => 7, 'onTime' => true, 'bookIndex' => 28],
            ['weeksAgo' => 11, 'days' => 10, 'onTime' => true, 'bookIndex' => 30],
            ['weeksAgo' => 12, 'days' => 8, 'onTime' => true, 'bookIndex' => 32],
            ['weeksAgo' => 14, 'days' => 11, 'onTime' => true, 'bookIndex' => 35],
            ['weeksAgo' => 16, 'days' => 14, 'onTime' => true, 'bookIndex' => 38],
            ['weeksAgo' => 18, 'days' => 20, 'onTime' => false, 'bookIndex' => 40], // late
            ['weeksAgo' => 20, 'days' => 9, 'onTime' => true, 'bookIndex' => 42],
            ['weeksAgo' => 22, 'days' => 12, 'onTime' => true, 'bookIndex' => 45],
            ['weeksAgo' => 25, 'days' => 7, 'onTime' => true, 'bookIndex' => 48],
            ['weeksAgo' => 28, 'days' => 13, 'onTime' => true, 'bookIndex' => 50],
            ['weeksAgo' => 30, 'days' => 10, 'onTime' => true, 'bookIndex' => 52],
            ['weeksAgo' => 34, 'days' => 14, 'onTime' => true, 'bookIndex' => 55],
            ['weeksAgo' => 38, 'days' => 8, 'onTime' => true, 'bookIndex' => 58],
            ['weeksAgo' => 42, 'days' => 11, 'onTime' => true, 'bookIndex' => 60],
        ];

        foreach ($historyPlan as $item) {
            $book = $books[$item['bookIndex'] % $books->count()];
            $borrowedDate = $now->copy()->subWeeks($item['weeksAgo'])->startOfWeek()->addDays($item['bookIndex'] % 5);
            $dueDate = $borrowedDate->copy()->addDays(14);
            $returnedDate = $item['onTime'] ? $borrowedDate->copy()->addDays($item['days']) : $dueDate->copy()->addDays(3);

            BorrowRecord::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                    'borrowed_at' => $borrowedDate->toDateString(),
                ],
                [
                    'reviewed_by' => $admin?->id,
                    'due_date' => $dueDate->toDateString(),
                    'returned_at' => $returnedDate->toDateString(),
                    'reviewed_at' => $borrowedDate->copy()->addMinutes(15),
                    'status' => 'returned',
                ]
            );
        }

        // Recalculate stats and badges for this user
        $journeyService->recalculateFor($user);
        $journeyService->syncBadgesFor($user);
    }
}
