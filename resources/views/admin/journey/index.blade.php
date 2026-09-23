<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">Gamification & Analytics</p>
                <h2 class="page-title text-slate-900">
                    {{ __('สถิตินักอ่าน & จัดการเหรียญรางวัล') }}
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">ภาพรวมสถิติการอ่าน แต้มสะสม ลีดเดอร์บอร์ด และจัดการเหรียญรางวัลของสมาชิกในระบบ</p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <form action="{{ route('admin.journey.recalculate-all') }}" method="POST" onsubmit="return confirm('ต้องการคำนวณแต้มและเหรียญของสมาชิกทุกคนใหม่ทั้งหมดหรือไม่?');">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>คำนวณแต้มใหม่ทุกคน</span>
                    </button>
                </form>
                <a href="{{ route('admin.badges.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-emerald-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>เพิ่มเหรียญรางวัลใหม่</span>
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

            {{-- 4 Quick Stat Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">สมาชิกนักอ่าน</span>
                        <span class="p-2 rounded-xl bg-blue-50 text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </span>
                    </div>
                    <div class="mt-3 text-3xl font-extrabold text-slate-900">{{ number_format($totalReaders) }}</div>
                    <div class="text-xs text-slate-500 mt-1">ผู้ใช้ในระบบทั้งหมด</div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">แต้มสะสมทั้งหมด</span>
                        <span class="p-2 rounded-xl bg-amber-50 text-amber-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        </span>
                    </div>
                    <div class="mt-3 text-3xl font-extrabold text-amber-600">{{ number_format($totalPointsIssued) }}</div>
                    <div class="text-xs text-slate-500 mt-1">XP ที่มอบให้สมาชิกแล้ว</div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">หนังสือที่อ่านจบแล้ว</span>
                        <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </span>
                    </div>
                    <div class="mt-3 text-3xl font-extrabold text-emerald-600">{{ number_format($totalBooksRead) }}</div>
                    <div class="text-xs text-slate-500 mt-1">เล่มที่ยืมและคืนสำเร็จ</div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">เหรียญที่ปลดล็อก</span>
                        <span class="p-2 rounded-xl bg-purple-50 text-purple-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        </span>
                    </div>
                    <div class="mt-3 text-3xl font-extrabold text-purple-600">{{ number_format($totalBadgesUnlocked) }}</div>
                    <div class="text-xs text-slate-500 mt-1">จากเหรียญทั้งหมด {{ $totalBadges }} แบบ</div>
                </div>
            </div>

            <!-- Top Readers Leaderboard -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">
                            ลีดเดอร์บอร์ดสมาชิกยอดนักอ่าน (Top 5)
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

            <!-- Integrated Badges Management Section -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">จัดการเหรียญรางวัลในระบบ (Badges Management)</h3>
                        <p class="text-xs text-slate-500 mt-0.5">รายการเหรียญรางวัล เงื่อนไขการปลดล็อก และการแก้ไขเหรียญ</p>
                    </div>
                    <a href="{{ route('admin.badges.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-3.5 py-2 text-xs font-bold text-white hover:bg-emerald-700 transition shrink-0">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>สร้างเหรียญรางวัลใหม่</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($badges as $badge)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50/50 p-5 flex flex-col justify-between hover:bg-slate-100/50 transition">
                            <div>
                                <div class="flex items-center justify-between gap-3 mb-3">
                                    <span class="text-xs font-semibold text-slate-500">#{{ $badge->sort_order }} {{ $badge->code }}</span>
                                    <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $badge->tier === 'gold' ? 'bg-amber-100 text-amber-800 border border-amber-200' : ($badge->tier === 'silver' ? 'bg-slate-200 text-slate-700 border border-slate-300' : ($badge->tier === 'bronze' ? 'bg-amber-800/10 text-amber-900 border border-amber-800/20' : 'bg-purple-100 text-purple-800 border border-purple-200')) }}">
                                        {{ $badge->tier }}
                                    </span>
                                </div>

                                <h4 class="text-base font-bold text-slate-900">{{ $badge->name_th }}</h4>
                                <p class="text-xs text-slate-600 mt-1 line-clamp-2">{{ $badge->description }}</p>

                                <div class="mt-3 pt-3 border-t border-slate-200/60 text-xs text-slate-500 space-y-1">
                                    <div class="flex justify-between">
                                        <span>เงื่อนไข:</span>
                                        <span class="font-bold text-slate-800">
                                            @if($badge->condition_type === 'books_read')
                                                อ่านครบ {{ $badge->condition_value }} เล่ม
                                            @elseif($badge->condition_type === 'categories_explored')
                                                สำรวจครบ {{ $badge->condition_value }} หมวด
                                            @elseif($badge->condition_type === 'on_time_streak_weeks')
                                                Streak {{ $badge->condition_value }} สัปดาห์
                                            @else
                                                แอดมินมอบให้พิเศษ
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>ผู้ได้รับแล้ว:</span>
                                        <span class="font-bold text-emerald-600">{{ $badge->users_count }} คน</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center justify-end gap-2">
                                <a href="{{ route('admin.badges.edit', $badge) }}" class="rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                                    แก้ไข
                                </a>
                                <form action="{{ route('admin.badges.destroy', $badge) }}" method="POST" onsubmit="return confirm('ยืนยันที่จะลบเหรียญรางวัล {{ $badge->name_th }} หรือไม่?');" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">
                                        ลบ
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-8 text-center text-xs text-slate-500">
                            ยังไม่มีเหรียญรางวัลในระบบ
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Members List & Stats Table -->
            <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">รายชื่อและสถิติสมาชิก (Members Stats)</h3>
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
                                    <td class="px-6 py-4 font-bold text-amber-600 flex items-center gap-1">
                                        <svg class="h-4 w-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                        <span>{{ number_format($stat->total_points ?? 0) }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-800">
                                        <span>{{ number_format($stat->total_books_read ?? 0) }} เล่ม</span>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-800">
                                        <span>{{ $stat->current_streak_weeks ?? 0 }} สัปดาห์</span>
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
