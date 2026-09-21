<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\BorrowRecord;
use App\Services\ReadingJourneyService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReadingJourneyController extends Controller
{
    /**
     * Display the member's reading journey dashboard.
     */
    public function index(Request $request, ReadingJourneyService $journeyService): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->readingStat === null) {
            $journeyService->recalculateFor($user);
            $journeyService->syncBadgesFor($user);
            $user->unsetRelation('readingStat');
            $user->unsetRelation('badges');
        }

        $user->loadMissing(['readingStat', 'badges']);
        $stat = $user->readingStat;

        $returnedRecords = BorrowRecord::query()
            ->with(['book.category'])
            ->whereBelongsTo($user)
            ->where('status', 'returned')
            ->orderByDesc('returned_at')
            ->orderByDesc('id')
            ->get();

        $bookshelfItems = $returnedRecords->map(function (BorrowRecord $record): array {
            $book = $record->book;
            $bookId = $book->id ?? 0;
            $categoryId = $book->category_id ?? 1;

            // Rich, high-contrast book spine color themes with inline linear gradients
            $colorThemes = [
                0 => [
                    'name' => 'ruby',
                    'bg_gradient' => 'linear-gradient(180deg, #9f1239 0%, #4c0519 100%)',
                    'text_color' => '#ffe4e6',
                    'foil_color' => '#fbbf24',
                    'accent_bg' => '#fb7185',
                    'ribbon_color' => '#f43f5e',
                ],
                1 => [
                    'name' => 'emerald',
                    'bg_gradient' => 'linear-gradient(180deg, #047857 0%, #022c22 100%)',
                    'text_color' => '#d1fae5',
                    'foil_color' => '#fde047',
                    'accent_bg' => '#34d399',
                    'ribbon_color' => '#10b981',
                ],
                2 => [
                    'name' => 'sapphire',
                    'bg_gradient' => 'linear-gradient(180deg, #1d4ed8 0%, #0f172a 100%)',
                    'text_color' => '#dbeafe',
                    'foil_color' => '#93c5fd',
                    'accent_bg' => '#60a5fa',
                    'ribbon_color' => '#3b82f6',
                ],
                3 => [
                    'name' => 'amber',
                    'bg_gradient' => 'linear-gradient(180deg, #b45309 0%, #451a03 100%)',
                    'text_color' => '#fef3c7',
                    'foil_color' => '#fde047',
                    'accent_bg' => '#fbbf24',
                    'ribbon_color' => '#f59e0b',
                ],
                4 => [
                    'name' => 'amethyst',
                    'bg_gradient' => 'linear-gradient(180deg, #6b21a8 0%, #2e1065 100%)',
                    'text_color' => '#f3e8ff',
                    'foil_color' => '#f0abfc',
                    'accent_bg' => '#c084fc',
                    'ribbon_color' => '#a855f7',
                ],
                5 => [
                    'name' => 'teal',
                    'bg_gradient' => 'linear-gradient(180deg, #0f766e 0%, #042f2e 100%)',
                    'text_color' => '#ccfbf1',
                    'foil_color' => '#5eead4',
                    'accent_bg' => '#2dd4bf',
                    'ribbon_color' => '#14b8a6',
                ],
                6 => [
                    'name' => 'terracotta',
                    'bg_gradient' => 'linear-gradient(180deg, #c2410c 0%, #431407 100%)',
                    'text_color' => '#ffedd5',
                    'foil_color' => '#fed7aa',
                    'accent_bg' => '#fb923c',
                    'ribbon_color' => '#ea580c',
                ],
                7 => [
                    'name' => 'slate',
                    'bg_gradient' => 'linear-gradient(180deg, #334155 0%, #090d16 100%)',
                    'text_color' => '#f1f5f9',
                    'foil_color' => '#cbd5e1',
                    'accent_bg' => '#94a3b8',
                    'ribbon_color' => '#64748b',
                ],
            ];

            $theme = $colorThemes[($categoryId + $bookId) % 8];
            $hasRibbon = ($bookId % 3 === 0);
            $tilt = match ($bookId % 13) {
                0 => -3,
                1 => 2,
                default => 0,
            };

            return [
                'record' => $record,
                'book' => $book,
                'height' => 135 + (($bookId * 17) % 42), // 135px - 176px tall
                'width' => 36 + (($bookId * 7) % 18),     // 36px - 53px wide
                'theme' => $theme,
                'has_ribbon' => $hasRibbon,
                'tilt' => $tilt,
                'pattern' => $bookId % 4,
            ];
        });

        $shelves = $bookshelfItems->chunk(12);

        $timelineRecords = $returnedRecords->take(10);

        $allBadges = Badge::query()->orderBy('sort_order')->get();
        $unlockedBadges = $user->badges->keyBy('id');

        $heatmap = $this->generateHeatmap($user);

        return view('journey.index', [
            'user' => $user,
            'stat' => $stat,
            'shelves' => $shelves,
            'timelineRecords' => $timelineRecords,
            'allBadges' => $allBadges,
            'unlockedBadges' => $unlockedBadges,
            'heatmap' => $heatmap,
            'totalBooksRead' => $returnedRecords->count(),
        ]);
    }

    /**
     * Build 53-week reading activity matrix for heatmap visualization.
     *
     * @return array{weeks: array<int, array<int, array<string, mixed>>>, months: array<int, array{name: string, col: int}>}
     */
    private function generateHeatmap($user): array
    {
        $now = Carbon::now();
        $startDate = $now->copy()->subWeeks(52)->startOfWeek(Carbon::MONDAY);
        $endDate = $now->copy()->endOfWeek(Carbon::SUNDAY);

        $borrowCounts = BorrowRecord::query()
            ->whereBelongsTo($user)
            ->whereNotNull('borrowed_at')
            ->where('borrowed_at', '>=', $startDate->toDateString())
            ->selectRaw('DATE(borrowed_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day')
            ->all();

        $weeks = [];
        $months = [];
        $cursor = $startDate->copy();
        $weekIndex = 0;
        $lastMonthName = null;

        while ($cursor->lte($endDate)) {
            $weekDays = [];
            for ($d = 0; $d < 7; $d++) {
                $dateStr = $cursor->toDateString();
                $count = (int) ($borrowCounts[$dateStr] ?? 0);

                $level = match (true) {
                    $count === 0 => 0,
                    $count === 1 => 1,
                    $count === 2 => 2,
                    $count <= 4 => 3,
                    default => 4,
                };

                $monthName = $cursor->locale('th')->translatedFormat('M');
                if ($lastMonthName !== $monthName && $cursor->day <= 7) {
                    $months[] = [
                        'name' => $monthName,
                        'col' => $weekIndex,
                    ];
                    $lastMonthName = $monthName;
                }

                $weekDays[] = [
                    'date' => $dateStr,
                    'count' => $count,
                    'level' => $level,
                    'thai_date' => $cursor->locale('th')->translatedFormat('j M Y'),
                    'is_future' => $cursor->isAfter($now),
                ];

                $cursor->addDay();
            }
            $weeks[] = $weekDays;
            $weekIndex++;
        }

        return [
            'weeks' => $weeks,
            'months' => $months,
        ];
    }
}
