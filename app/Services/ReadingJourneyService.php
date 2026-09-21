<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\BorrowRecord;
use App\Models\ReadingStat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReadingJourneyService
{
    public const int POINTS_RETURNED = 10;

    public const int POINTS_ON_TIME = 5;

    public const int POINTS_LATE_PENALTY = 3;

    public const int POINTS_PER_CATEGORY = 15;

    public const int POINTS_PER_LEVEL = 100;

    /**
     * Recalculate reading statistics for a given user.
     */
    public function recalculateFor(User $user): ReadingStat
    {
        $returnedBorrows = BorrowRecord::query()
            ->with('book')
            ->where('user_id', $user->id)
            ->where('status', 'returned')
            ->get();

        $totalBooksRead = $returnedBorrows->count();
        $onTimeReturns = 0;
        $lateReturns = 0;
        $categoriesExploredIds = [];

        foreach ($returnedBorrows as $borrow) {
            if ($borrow->due_date === null || ($borrow->returned_at !== null && $borrow->returned_at->lte($borrow->due_date))) {
                $onTimeReturns++;
            } else {
                $lateReturns++;
            }

            if ($borrow->book?->category_id !== null) {
                $categoriesExploredIds[$borrow->book->category_id] = true;
            }
        }

        $categoriesExplored = count($categoriesExploredIds);

        $points = ($totalBooksRead * self::POINTS_RETURNED)
            + ($onTimeReturns * self::POINTS_ON_TIME)
            + ($categoriesExplored * self::POINTS_PER_CATEGORY)
            - ($lateReturns * self::POINTS_LATE_PENALTY);

        $totalPoints = max(0, $points);

        [$currentStreak, $longestStreak] = $this->calculateStreaks($user);

        /** @var ReadingStat $stat */
        $stat = ReadingStat::query()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'total_books_read' => $totalBooksRead,
                'total_points' => $totalPoints,
                'current_streak' => $currentStreak,
                'longest_streak' => $longestStreak,
                'on_time_returns' => $onTimeReturns,
                'late_returns' => $lateReturns,
                'categories_explored' => $categoriesExplored,
                'last_calculated_at' => now(),
            ]
        );

        return $stat;
    }

    /**
     * Synchronize and unlock newly achieved badges for a user.
     *
     * @return Collection<int, Badge> Newly unlocked badges
     */
    public function syncBadgesFor(User $user): Collection
    {
        $stat = $user->readingStat ?? $this->recalculateFor($user);
        $badges = Badge::query()->orderBy('sort_order')->get();

        $existingBadgeIds = DB::table('user_badges')
            ->where('user_id', $user->id)
            ->pluck('badge_id')
            ->all();

        $newlyUnlocked = collect();

        foreach ($badges as $badge) {
            if (in_array($badge->id, $existingBadgeIds, true)) {
                continue;
            }

            $isEligible = match ($badge->condition_type) {
                'books_read' => $stat->total_books_read >= $badge->condition_value,
                'on_time' => $stat->on_time_returns >= $badge->condition_value,
                'category_variety' => $stat->categories_explored >= $badge->condition_value,
                'streak' => max($stat->current_streak, $stat->longest_streak) >= $badge->condition_value,
                default => false,
            };

            if ($isEligible) {
                try {
                    DB::table('user_badges')->insert([
                        'user_id' => $user->id,
                        'badge_id' => $badge->id,
                        'unlocked_at' => now(),
                    ]);
                    $newlyUnlocked->push($badge);
                } catch (QueryException) {
                    // Ignore duplicate key race conditions
                }
            }
        }

        return $newlyUnlocked;
    }

    /**
     * Calculate current and longest weekly borrow streaks.
     *
     * @return array{0: int, 1: int} [current_streak, longest_streak]
     */
    private function calculateStreaks(User $user): array
    {
        $borrowDates = BorrowRecord::query()
            ->where('user_id', $user->id)
            ->whereNotNull('borrowed_at')
            ->pluck('borrowed_at');

        if ($borrowDates->isEmpty()) {
            return [0, 0];
        }

        $activeWeeks = [];
        foreach ($borrowDates as $date) {
            $carbonDate = Carbon::parse($date);
            $key = $carbonDate->isoWeekYear.'-'.str_pad((string) $carbonDate->isoWeek, 2, '0', STR_PAD_LEFT);
            $activeWeeks[$key] = true;
        }

        if (empty($activeWeeks)) {
            return [0, 0];
        }

        $now = now();
        $currentIsoKey = $now->isoWeekYear.'-'.str_pad((string) $now->isoWeek, 2, '0', STR_PAD_LEFT);
        $lastWeek = $now->copy()->subWeek();
        $lastWeekIsoKey = $lastWeek->isoWeekYear.'-'.str_pad((string) $lastWeek->isoWeek, 2, '0', STR_PAD_LEFT);

        // Calculate current streak
        $currentStreak = 0;
        $checkDate = isset($activeWeeks[$currentIsoKey]) ? $now->copy() : (isset($activeWeeks[$lastWeekIsoKey]) ? $lastWeek->copy() : null);

        if ($checkDate !== null) {
            while (true) {
                $weekKey = $checkDate->isoWeekYear.'-'.str_pad((string) $checkDate->isoWeek, 2, '0', STR_PAD_LEFT);
                if (! isset($activeWeeks[$weekKey])) {
                    break;
                }
                $currentStreak++;
                $checkDate->subWeek();
            }
        }

        // Calculate longest streak across all history
        $sortedWeeks = array_keys($activeWeeks);
        sort($sortedWeeks);

        $longestStreak = 0;
        $tempStreak = 0;
        $previousWeekDate = null;

        foreach ($sortedWeeks as $weekStr) {
            [$year, $weekNum] = explode('-', $weekStr);
            $weekDate = Carbon::now()->setISODate((int) $year, (int) $weekNum, 1)->startOfDay();

            if ($previousWeekDate === null) {
                $tempStreak = 1;
            } else {
                $diffInWeeks = (int) round($previousWeekDate->diffInDays($weekDate) / 7);
                if ($diffInWeeks === 1) {
                    $tempStreak++;
                } else {
                    $tempStreak = 1;
                }
            }

            $longestStreak = max($longestStreak, $tempStreak);
            $previousWeekDate = $weekDate;
        }

        return [$currentStreak, max($longestStreak, $currentStreak)];
    }

    /**
     * Manually award a badge to a user.
     */
    public function awardBadge(User $user, Badge $badge): bool
    {
        if ($user->badges()->where('badge_id', $badge->id)->exists()) {
            return false;
        }

        $user->badges()->attach($badge->id, ['unlocked_at' => now()]);

        return true;
    }

    /**
     * Manually revoke a badge from a user.
     */
    public function revokeBadge(User $user, Badge $badge): bool
    {
        return (bool) $user->badges()->detach($badge->id);
    }

    /**
     * Manually adjust points for a user.
     */
    public function adjustPoints(User $user, int $additionalPoints): ReadingStat
    {
        $stat = $user->readingStat ?? $this->recalculateFor($user);
        $newPoints = max(0, $stat->total_points + $additionalPoints);
        $newLevel = (int) floor($newPoints / self::POINTS_PER_LEVEL) + 1;

        $stat->update([
            'total_points' => $newPoints,
            'level' => $newLevel,
        ]);

        return $stat->fresh();
    }
}
