<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-emerald-600">Badges Configuration</p>
                <h2 class="page-title text-slate-900">
                    {{ __('จัดการเหรียญรางวัล (Badges)') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">กำหนดรายการเหรียญรางวัลและเงื่อนไขการปลดล็อกในระบบ</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.journey.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 shadow-sm transition">
                    ← สถิตินักอ่าน
                </a>
                <a href="{{ route('admin.badges.create') }}" class="rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-bold text-slate-950 shadow-sm hover:bg-amber-400 transition">
                    + เพิ่มเหรียญใหม่
                </a>
            </div>
        </div>
    </x-slot>

    <div class="page-shell">
        <div class="page-container space-y-6">
            
            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($badges as $badge)
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 flex flex-col justify-between shadow-sm hover:border-slate-300 transition">
                        <div>
                            <div class="flex items-center justify-between gap-3 mb-4">
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-3xl border border-slate-200 shadow-sm">
                                    {{ $badge->icon }}
                                </div>
                                <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wider {{ $badge->tier === 'gold' ? 'bg-amber-100 text-amber-800 border border-amber-200' : ($badge->tier === 'silver' ? 'bg-slate-100 text-slate-700 border border-slate-200' : ($badge->tier === 'bronze' ? 'bg-amber-800/10 text-amber-900 border border-amber-800/20' : 'bg-purple-100 text-purple-800 border border-purple-200')) }}">
                                    {{ $badge->tier }}
                                </span>
                            </div>

                            <h3 class="text-lg font-bold text-slate-900">{{ $badge->name_th }}</h3>
                            <p class="text-xs text-slate-400 font-mono mt-0.5">Code: {{ $badge->code }}</p>
                            <p class="text-sm text-slate-600 mt-3">{{ $badge->description }}</p>

                            <div class="mt-4 pt-4 border-t border-slate-100 text-xs text-slate-500 space-y-1.5">
                                <div class="flex justify-between">
                                    <span>เงื่อนไขการปลดล็อก:</span>
                                    <span class="font-bold text-slate-900">
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
                                    <span>ผู้ได้รับเหรียญนี้แล้ว:</span>
                                    <span class="font-bold text-emerald-600">{{ $badge->users_count }} คน</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs text-slate-400">ลำดับ: {{ $badge->sort_order }}</span>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.badges.edit', $badge) }}" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 shadow-sm">
                                    แก้ไข
                                </a>
                                <form action="{{ route('admin.badges.destroy', $badge) }}" method="POST" onsubmit="return confirm('ยืนยันที่จะลบเหรียญนี้หรือไม่?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-100">
                                        ลบ
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 py-12 text-center text-slate-500">
                        ยังไม่มีข้อมูลเหรียญรางวัลในระบบ
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
