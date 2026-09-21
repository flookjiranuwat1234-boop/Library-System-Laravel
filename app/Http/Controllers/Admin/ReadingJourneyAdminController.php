<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\ReadingStat;
use App\Models\User;
use App\Services\ReadingJourneyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReadingJourneyAdminController extends Controller
{
    public function __construct(
        protected ReadingJourneyService $readingJourneyService
    ) {}

    public function index(Request $request): View
    {
        $search = $request->query('search');

        $usersQuery = User::query()
            ->where('role', 'user')
            ->with(['readingStat', 'badges'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });

        $users = $usersQuery->paginate(15)->withQueryString();

        // Top readers leaderboard
        $topReaders = ReadingStat::query()
            ->with('user')
            ->whereHas('user', fn ($q) => $q->where('role', 'user'))
            ->orderByDesc('total_points')
            ->take(5)
            ->get();

        // Statistics overview
        $totalReaders = User::where('role', 'user')->count();
        $totalPointsIssued = ReadingStat::sum('total_points') ?? 0;
        $totalBooksRead = ReadingStat::sum('total_books_read') ?? 0;
        $totalBadgesUnlocked = DB::table('user_badges')->count();
        $totalBadges = Badge::count();

        return view('admin.journey.index', [
            'users' => $users,
            'topReaders' => $topReaders,
            'totalReaders' => $totalReaders,
            'totalPointsIssued' => $totalPointsIssued,
            'totalBooksRead' => $totalBooksRead,
            'totalBadgesUnlocked' => $totalBadgesUnlocked,
            'totalBadges' => $totalBadges,
            'search' => $search,
        ]);
    }

    public function show(User $user): View
    {
        $user->loadMissing(['readingStat', 'badges', 'borrowRecords.book.category']);

        $stat = $user->readingStat ?? $this->readingJourneyService->recalculateFor($user);
        $allBadges = Badge::orderBy('sort_order')->get();
        $userBadgeIds = $user->badges->pluck('id')->toArray();

        $recentBorrows = $user->borrowRecords()
            ->with('book.category')
            ->latest('borrowed_at')
            ->take(10)
            ->get();

        return view('admin.journey.show', [
            'user' => $user,
            'stat' => $stat,
            'allBadges' => $allBadges,
            'userBadgeIds' => $userBadgeIds,
            'recentBorrows' => $recentBorrows,
        ]);
    }

    public function recalculate(User $user): RedirectResponse
    {
        $this->readingJourneyService->recalculateFor($user);
        $this->readingJourneyService->syncBadgesFor($user);

        return back()->with('success', "คำนวณคะแนนและเหรียญของ {$user->name} ใหม่เรียบร้อยแล้ว");
    }

    public function recalculateAll(): RedirectResponse
    {
        $users = User::where('role', 'user')->get();

        foreach ($users as $user) {
            $this->readingJourneyService->recalculateFor($user);
            $this->readingJourneyService->syncBadgesFor($user);
        }

        return back()->with('success', "คำนวณคะแนนและเหรียญรางวัลของสมาชิกทุกคนใหม่เรียบร้อยแล้ว ({$users->count()} คน)");
    }

    public function awardBadge(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'badge_id' => 'required|exists:badges,id',
        ]);

        $badge = Badge::findOrFail($request->input('badge_id'));
        $awarded = $this->readingJourneyService->awardBadge($user, $badge);

        if ($awarded) {
            return back()->with('success', "มอบเหรียญรางวัล '{$badge->name_th}' ให้ {$user->name} สำเร็จ");
        }

        return back()->with('warning', "{$user->name} ได้รับเหรียญรางวัลนี้อยู่แล้ว");
    }

    public function revokeBadge(User $user, Badge $badge): RedirectResponse
    {
        $this->readingJourneyService->revokeBadge($user, $badge);

        return back()->with('success', "ยกเลิกเหรียญรางวัล '{$badge->name_th}' ของ {$user->name} เรียบร้อยแล้ว");
    }

    public function adjustPoints(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'points' => 'required|integer',
            'reason' => 'nullable|string|max:255',
        ]);

        $points = (int) $request->input('points');
        $this->readingJourneyService->adjustPoints($user, $points);

        $action = $points >= 0 ? "เพิ่มแต้ม +{$points}" : "ลดแต้ม {$points}";

        return back()->with('success', "{$action} แต้มให้ {$user->name} เรียบร้อยแล้ว");
    }
}
