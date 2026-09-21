<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.journey.index') }}" class="rounded-xl border border-slate-200 bg-white p-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 shadow-sm transition">
                    ←
                </a>
                <div>
                    <h2 class="page-title text-slate-900">
                        สถิติการอ่าน: {{ $user->name }}
                    </h2>
                    <p class="text-sm text-slate-500 mt-0.5">{{ $user->email }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <form action="{{ route('admin.journey.recalculate', $user) }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition">
                        🔄 คำนวณแต้มใหม่
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="page-shell">
        <div class="page-container space-y-8">
            
            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="rounded-xl border border-amber-300 bg-amber-50 p-4 text-amber-800 text-sm">
                    {{ session('warning') }}
                </div>
            @endif

            <!-- User Stats Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <span class="text-xs font-semibold text-slate-500 uppercase">ระดับ (Level)</span>
                    <div class="mt-2 text-3xl font-extrabold text-indigo-600">Lv. {{ $stat->level }}</div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <span class="text-xs font-semibold text-slate-500 uppercase">แต้มรวม (XP)</span>
                    <div class="mt-2 text-3xl font-extrabold text-amber-600">⭐ {{ number_format($stat->total_points) }}</div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <span class="text-xs font-semibold text-slate-500 uppercase">อ่านจบแล้ว</span>
                    <div class="mt-2 text-3xl font-extrabold text-emerald-600">📖 {{ number_format($stat->total_books_read) }} เล่ม</div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <span class="text-xs font-semibold text-slate-500 uppercase">หมวดหมู่ที่สำรวจ</span>
                    <div class="mt-2 text-3xl font-extrabold text-cyan-600">🏷️ {{ $stat->categories_explored }} หมวด</div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <span class="text-xs font-semibold text-slate-500 uppercase">สถิติ Streak</span>
                    <div class="mt-2 text-3xl font-extrabold text-rose-600">🔥 {{ $stat->current_streak_weeks }} สัปดาห์</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Badges Control & Management -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">🎖️ เหรียญรางวัลของสมาชิก (Badges)</h3>
                                <p class="text-xs text-slate-500">ได้รับแล้ว {{ count($userBadgeIds) }} จาก {{ $allBadges->count() }} เหรียญ</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @foreach($allBadges as $badge)
                                @php
                                    $isUnlocked = in_array($badge->id, $userBadgeIds);
                                    $tierBg = match($badge->tier) {
                                        'gold' => 'bg-amber-100 text-amber-900 border-amber-300',
                                        'silver' => 'bg-slate-200 text-slate-800 border-slate-300',
                                        'bronze' => 'bg-amber-900/10 text-amber-900 border-amber-900/20',
                                        'special', 'platinum' => 'bg-purple-100 text-purple-900 border-purple-200',
                                        default => 'bg-slate-100 text-slate-800 border-slate-200',
                                    };
                                @endphp
                                <div class="flex items-center justify-between rounded-xl border p-4 transition {{ $isUnlocked ? 'border-amber-300 bg-amber-50/30' : 'border-slate-200 bg-slate-50/50 opacity-60' }}">
                                    <div class="flex items-center gap-3.5 min-w-0 flex-1">
                                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white text-2xl shadow-xs border border-slate-200">
                                            {{ $badge->icon }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h4 class="font-bold text-slate-900 text-sm truncate">{{ $badge->name_th }}</h4>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="shrink-0 inline-flex items-center justify-center text-[10px] leading-none font-bold uppercase tracking-wider px-2 py-1 rounded border {{ $tierBg }}">
                                                    {{ $badge->tier }}
                                                </span>
                                                <p class="text-xs text-slate-500 truncate" title="{{ $badge->description }}">{{ $badge->description }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="shrink-0 pl-4">
                                        @if($isUnlocked)
                                            <form action="{{ route('admin.journey.revoke-badge', [$user, $badge]) }}" method="POST" onsubmit="return confirm('ต้องการยกเลิกเหรียญนี้จากผู้ใช้หรือไม่?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-1 rounded-lg border border-rose-200 bg-white px-3 py-1.5 text-xs font-bold text-rose-600 shadow-xs hover:bg-rose-50 hover:border-rose-300 transition">
                                                    ✕ ปลดออก
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.journey.award-badge', $user) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="badge_id" value="{{ $badge->id }}">
                                                <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-emerald-600 px-3.5 py-1.5 text-xs font-bold text-white shadow-xs hover:bg-emerald-700 transition">
                                                    + มอบให้
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Recent Borrow History -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-slate-900 mb-4">📚 ประวัติการยืม-คืนล่าสุด</h3>
                        <div class="space-y-3">
                            @forelse($recentBorrows as $borrow)
                                <div class="flex items-center justify-between rounded-xl bg-slate-50 border border-slate-200 p-3">
                                    <div>
                                        <div class="font-bold text-slate-900 text-sm">{{ $borrow->book->title ?? 'หนังสือไม่ระบุ' }}</div>
                                        <div class="text-xs text-slate-500">หมวดหมู่: {{ $borrow->book->category->name ?? '-' }} | วันที่ยืม: {{ $borrow->borrowed_at?->format('d/m/Y') }}</div>
                                    </div>
                                    <span class="rounded-lg px-2.5 py-1 text-xs font-bold {{ $borrow->status === 'returned' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $borrow->status === 'returned' ? 'คืนแล้ว' : 'กำลังยืม' }}
                                    </span>
                                </div>
                            @empty
                                <div class="text-sm text-slate-500 text-center py-4">ไม่มีประวัติการยืม</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Right: Manual Points Adjustments -->
                <div class="space-y-6">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-slate-900 mb-2">⭐ ปรับปรุงแต้มพิเศษ</h3>
                        <p class="text-xs text-slate-500 mb-5">เพิ่มหรือหักแต้มสะสม (XP) ของสมาชิกโดยตรง</p>

                        <form action="{{ route('admin.journey.adjust-points', $user) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">จำนวนแต้มที่ต้องการปรับ (XP)</label>
                                <input type="number" name="points" placeholder="เช่น +50 หรือ -20" required class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-sm" />
                                <span class="text-[11px] text-slate-500 mt-1 block">ใส่จำนวนบวกเพื่อเพิ่ม หรือเครื่องหมายลบ (-) เพื่อหักแต้ม</span>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">เหตุผล (ไม่บังคับ)</label>
                                <input type="text" name="reason" placeholder="เช่น กิจกรรมพิเศษประจำสัปดาห์" class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-sm" />
                            </div>

                            <button type="submit" class="w-full rounded-xl bg-amber-500 py-2.5 text-sm font-bold text-slate-950 hover:bg-amber-400 shadow-sm transition">
                                บันทึกการปรับแต้ม
                            </button>
                        </form>
                    </div>

                    <div class="rounded-2xl border border-indigo-200 bg-indigo-50/50 p-6">
                        <h4 class="font-bold text-indigo-950 text-sm mb-2">💡 ข้อมูลการคำนวณอัตโนมัติ</h4>
                        <ul class="text-xs text-indigo-900 space-y-1.5 list-disc list-inside">
                            <li>คืนหนังสือสำเร็จ: <strong>+10 XP</strong></li>
                            <li>คืนตรงเวลา: <strong>+5 XP</strong></li>
                            <li>คืนล่าช้ากว่ากำหนด: <strong>-3 XP</strong></li>
                            <li>สำรวจหมวดหมู่ใหม่: <strong>+15 XP/หมวด</strong></li>
                            <li>ทุกๆ <strong>100 XP</strong> จะเลื่อนระดับ 1 เลเวล</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
