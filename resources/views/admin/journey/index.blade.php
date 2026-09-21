<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-emerald-600">Gamification & Analytics</p>
                <h2 class="page-title text-slate-900">
                    {{ __('ระบบติดตามเส้นทางการอ่าน & ลีดเดอร์บอร์ด') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">ภาพรวมสถิติการอ่าน แต้มสะสม และเหรียญรางวัลของสมาชิกทั้งหมด</p>
            </div>
            <div class="flex items-center gap-3">
                <form action="{{ route('admin.journey.recalculate-all') }}" method="POST" onsubmit="return confirm('ต้องการคำนวณแต้มและเหรียญของสมาชิกทุกคนใหม่ทั้งหมดหรือไม่?');">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        คำนวณแต้มใหม่ทั้งหมด
                    </button>
                </form>
                <a href="{{ route('admin.badges.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-bold text-slate-950 shadow-sm hover:bg-amber-400 transition">
                    🏆 จัดการเหรียญรางวัล
                </a>
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

            <!-- KPI Cards Overview -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-600">สมาชิกนักอ่าน</span>
                        <span class="p-2 rounded-xl bg-blue-50 text-blue-600 text-lg">👥</span>
                    </div>
                    <div class="mt-3 text-3xl font-extrabold text-slate-900">{{ number_format($totalReaders) }}</div>
                    <div class="text-xs text-slate-500 mt-1">ผู้ใช้ในระบบทั้งหมด</div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-600">แต้มสะสมทั้งหมด</span>
                        <span class="p-2 rounded-xl bg-amber-50 text-amber-600 text-lg">⭐</span>
                    </div>
                    <div class="mt-3 text-3xl font-extrabold text-amber-600">{{ number_format($totalPointsIssued) }}</div>
                    <div class="text-xs text-slate-500 mt-1">XP ที่มอบให้สมาชิกแล้ว</div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-600">หนังสือที่อ่านจบแล้ว</span>
                        <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600 text-lg">📖</span>
                    </div>
                    <div class="mt-3 text-3xl font-extrabold text-emerald-600">{{ number_format($totalBooksRead) }}</div>
                    <div class="text-xs text-slate-500 mt-1">เล่มที่ยืมและคืนสำเร็จ</div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-600">เหรียญที่ปลดล็อก</span>
                        <span class="p-2 rounded-xl bg-purple-50 text-purple-600 text-lg">🎖️</span>
                    </div>
                    <div class="mt-3 text-3xl font-extrabold text-purple-600">{{ number_format($totalBadgesUnlocked) }}</div>
                    <div class="text-xs text-slate-500 mt-1">จากเหรียญทั้งหมด {{ $totalBadges }} แบบ</div>
                </div>
            </div>

            <!-- Top Readers Leaderboard -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <span>🏆</span> ลีดเดอร์บอร์ดสมาชิกยอดนักอ่าน (Top 5)
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">สมาชิกที่มีคะแนนสะสมสูงสุดในระบบ</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
                    @forelse($topReaders as $index => $topStat)
                        <div class="relative rounded-2xl border border-slate-200 bg-slate-50/80 p-4 text-center transition hover:border-amber-400 hover:shadow-md">
                            <div class="absolute -top-3 left-1/2 -translate-x-1/2 flex h-6 w-6 items-center justify-center rounded-full text-xs font-black shadow-sm {{ $index === 0 ? 'bg-amber-400 text-slate-950 ring-2 ring-white' : ($index === 1 ? 'bg-slate-300 text-slate-900 ring-2 ring-white' : ($index === 2 ? 'bg-amber-700 text-white ring-2 ring-white' : 'bg-slate-700 text-white ring-2 ring-white')) }}">
                                #{{ $index + 1 }}
                            </div>
                            <div class="mt-2 flex h-12 w-12 mx-auto items-center justify-center rounded-full bg-slate-900 text-white text-base font-bold shadow-sm">
                                {{ mb_substr($topStat->user->name ?? 'U', 0, 1) }}
                            </div>
                            <h4 class="mt-3 text-sm font-bold text-slate-900 truncate">{{ $topStat->user->name ?? 'ไม่พบชื่อ' }}</h4>
                            <p class="text-xs text-slate-500 truncate">{{ $topStat->user->email ?? '-' }}</p>
                            <div class="mt-3 flex items-center justify-center gap-2 text-xs">
                                <span class="rounded-lg bg-indigo-50 border border-indigo-100 px-2 py-0.5 font-bold text-indigo-700">Lv. {{ $topStat->level }}</span>
                                <span class="rounded-lg bg-amber-50 border border-amber-200 px-2 py-0.5 font-bold text-amber-700">{{ number_format($topStat->total_points) }} แต้ม</span>
                            </div>
                            <div class="mt-3 pt-3 border-t border-slate-200">
                                <a href="{{ route('admin.journey.show', $topStat->user_id) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                                    ดูสถิติ & มอบรางวัล →
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-5 py-6 text-center text-sm text-slate-500">
                            ยังไม่มีข้อมูลสถิติการอ่าน
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Members List & Stats Table -->
            <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">รายชื่อและสถิติสมาชิก (Members Stats)</h3>
                        <p class="text-xs text-slate-500 mt-0.5">ค้นหา ตรวจสอบสถิติ และจัดการแต้ม/เหรียญรางวัลเป็นรายบุคคล</p>
                    </div>
                    <form method="GET" action="{{ route('admin.journey.index') }}" class="flex items-center gap-2">
                        <input type="text" name="search" value="{{ $search }}" placeholder="ค้นหาชื่อหรืออีเมล..." class="rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 w-64 shadow-sm" />
                        <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 transition">
                            ค้นหา
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-700">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-600 border-b border-slate-200 font-semibold">
                            <tr>
                                <th class="px-6 py-4">สมาชิก</th>
                                <th class="px-6 py-4">เลเวล</th>
                                <th class="px-6 py-4">แต้มสะสม (XP)</th>
                                <th class="px-6 py-4">อ่านจบ (เล่ม)</th>
                                <th class="px-6 py-4">Streak (สัปดาห์)</th>
                                <th class="px-6 py-4">เหรียญที่ได้</th>
                                <th class="px-6 py-4 text-right">การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($users as $user)
                                @php
                                    $stat = $user->readingStat;
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-900 text-xs font-bold text-white shadow-sm">
                                                {{ mb_substr($user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-900">{{ $user->name }}</div>
                                                <div class="text-xs text-slate-500">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-lg bg-indigo-50 border border-indigo-100 px-2.5 py-1 text-xs font-bold text-indigo-700">
                                            Lv. {{ $stat->level ?? 1 }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-amber-600">
                                        ⭐ {{ number_format($stat->total_points ?? 0) }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-800">
                                        📖 {{ number_format($stat->total_books_read ?? 0) }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-800">
                                        🔥 {{ $stat->current_streak_weeks ?? 0 }} สัปดาห์
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-purple-700 bg-purple-50 border border-purple-100 px-2 py-0.5 rounded-md text-xs">{{ $user->badges->count() }}</span>
                                            <span class="text-xs text-slate-500">เหรียญ</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <form action="{{ route('admin.journey.recalculate', $user) }}" method="POST">
                                                @csrf
                                                <button type="submit" title="คำนวณคะแนนใหม่" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                </button>
                                            </form>
                                            <a href="{{ route('admin.journey.show', $user) }}" class="rounded-xl bg-slate-900 px-3.5 py-1.5 text-xs font-bold text-white hover:bg-slate-800 shadow-sm transition">
                                                จัดการสถิติ & มอบเหรียญ →
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                        ไม่พบรายชื่อสมาชิก
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="p-4 border-t border-slate-200 bg-slate-50">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
