<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div><p class="text-sm font-semibold text-emerald-600">พื้นที่สมาชิก</p><h2 class="page-title">ภาพรวมของฉัน</h2></div>
            <p class="text-sm text-slate-500">{{ now()->locale('th')->translatedFormat('lที่ j F Y') }}</p>
        </div>
    </x-slot>

    <div class="page-shell"><div class="page-container space-y-7">
        <section class="relative overflow-hidden rounded-3xl bg-slate-950 px-6 py-8 text-white shadow-2xl shadow-slate-300/40 sm:px-8 lg:px-10">
            <div class="absolute -right-16 -top-20 h-64 w-64 rounded-full bg-emerald-500/20 blur-3xl"></div>
            <div class="relative grid gap-8 lg:grid-cols-[1.4fr_0.6fr] lg:items-center">
                <div><span class="inline-flex rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1 text-xs font-semibold text-emerald-300">ยินดีต้อนรับกลับ</span><h1 class="mt-4 text-3xl font-bold tracking-tight sm:text-4xl">สวัสดี {{ Auth::user()->name }}</h1><p class="mt-3 max-w-2xl text-slate-300">ค้นพบหนังสือเล่มใหม่ ติดตามกำหนดคืน และจัดการรายการยืมทั้งหมดได้จากที่เดียว</p><div class="mt-6 flex flex-wrap gap-3"><a href="{{ route('books.index') }}" class="button-primary">ค้นหาหนังสือ</a><a href="{{ route('borrows.index') }}" class="inline-flex items-center rounded-xl border border-white/15 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/20">ดูรายการยืม</a></div></div>
                <div class="hidden justify-self-end lg:block"><div class="flex h-40 w-40 rotate-3 items-center justify-center rounded-[2.5rem] border border-white/10 bg-white/5 text-7xl shadow-inner">📖</div></div>
            </div>
        </section>

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="panel flex items-center gap-4 p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </span>
                <div><p class="text-sm font-medium text-slate-500">หนังสือทั้งหมด</p><p class="text-3xl font-bold text-slate-900">{{ $totalBooks }}</p></div>
            </div>
            <div class="panel flex items-center gap-4 p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </span>
                <div><p class="text-sm font-medium text-slate-500">พร้อมให้ยืม</p><p class="text-3xl font-bold text-emerald-700">{{ $availableBooks }}</p></div>
            </div>
            <div class="panel flex items-center gap-4 p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-100 text-sky-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </span>
                <div><p class="text-sm font-medium text-slate-500">กำลังยืม</p><p class="text-3xl font-bold text-sky-700">{{ $activeBorrowings }}</p></div>
            </div>
        </section>

        <div class="grid gap-6 lg:grid-cols-[1.4fr_0.6fr]">
            <section class="panel p-5 sm:p-6"><div class="flex items-center justify-between gap-4"><div><h2 class="text-xl font-bold text-slate-900">หนังสือมาใหม่</h2><p class="text-sm text-slate-500">รายการล่าสุดที่เพิ่มเข้าคลัง</p></div><a href="{{ route('books.index') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-800">ดูทั้งหมด →</a></div>
                <div class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-4">@forelse($latestBooks as $book)<a href="{{ route('books.show', $book) }}" class="group"><div class="aspect-[3/4] overflow-hidden rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200">@if($book->coverImageUrl())<img src="{{ $book->coverImageUrl() }}" alt="{{ $book->title }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">@else<div class="flex h-full items-center justify-center text-4xl">📘</div>@endif</div><h3 class="mt-3 line-clamp-2 text-sm font-bold text-slate-900 group-hover:text-emerald-700">{{ $book->title }}</h3><p class="mt-1 truncate text-xs text-slate-500">{{ $book->author }}</p></a>@empty<p class="col-span-full py-10 text-center text-sm text-slate-500">ยังไม่มีหนังสือในคลัง</p>@endforelse</div>
            </section>
            <div class="space-y-6">
                <section class="panel p-5 sm:p-6">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">กำหนดคืน</h2>
                        <p class="text-sm text-slate-500">รายการที่ควรติดตาม</p>
                    </div>
                    <div class="mt-5 space-y-3">
                        @forelse($dueSoonBorrows as $borrow)
                            <a href="{{ route('borrows.index') }}" class="block rounded-2xl border border-slate-100 bg-slate-50 p-4 transition hover:border-emerald-200 hover:bg-emerald-50">
                                <p class="line-clamp-1 text-sm font-bold text-slate-900">{{ $borrow->book->title }}</p>
                                <div class="mt-2 flex items-center justify-between gap-2 text-xs">
                                    <span class="text-slate-500">{{ $borrow->due_date->locale('th')->translatedFormat('j M Y') }}</span>
                                    <span class="status-badge {{ $borrow->due_date->isPast() ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700' }}">{{ $borrow->due_date->isPast() ? 'เกินกำหนด' : $borrow->due_date->diffForHumans() }}</span>
                                </div>
                            </a>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-200 py-10 text-center">
                                <div class="text-3xl">✨</div>
                                <p class="mt-2 text-sm font-medium text-slate-600">ไม่มีรายการค้างคืน</p>
                            </div>
                        @endforelse
                    </div>
                </section>

                <!-- Reading Journey Summary Card -->
                <section class="panel p-5 sm:p-6 border-emerald-100 bg-gradient-to-br from-white to-emerald-50/30">
                    <div class="flex items-center justify-between gap-2">
                        <div>
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">
                                <span>🌱</span> ระดับ {{ $readingStat?->currentLevel() ?? 1 }}
                            </span>
                            <h2 class="mt-2 text-lg font-bold text-slate-900">เส้นทางการอ่าน</h2>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-bold text-emerald-600">{{ $readingStat?->total_points ?? 0 }}</span>
                            <p class="text-[11px] text-slate-500">แต้มสะสม</p>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-2 text-xs">
                        <div class="rounded-xl bg-white p-3 border border-slate-100 shadow-xs">
                            <p class="text-slate-500">อ่านจบแล้ว</p>
                            <p class="text-base font-bold text-slate-900">{{ $readingStat?->total_books_read ?? 0 }} เล่ม</p>
                        </div>
                        <div class="rounded-xl bg-white p-3 border border-slate-100 shadow-xs">
                            <p class="text-slate-500">ความต่อเนื่อง</p>
                            <p class="text-base font-bold text-rose-600">🔥 {{ $readingStat?->current_streak ?? 0 }} สัปดาห์</p>
                        </div>
                    </div>

                    @if(isset($recentBadges) && $recentBadges->isNotEmpty())
                        <div class="mt-4 pt-3 border-t border-slate-100">
                            <p class="text-xs font-medium text-slate-500 mb-2">ตราล่าสุดที่ได้รับ:</p>
                            <div class="flex items-center gap-2">
                                @foreach($recentBadges as $badge)
                                    <span class="inline-flex items-center gap-1 rounded-lg bg-white px-2 py-1 text-xs font-medium text-slate-700 border border-slate-200/80 shadow-xs" title="{{ $badge->name_th }}">
                                        <span>{{ $badge->icon }}</span>
                                        <span class="truncate max-w-[80px]">{{ $badge->name_th }}</span>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-5">
                        <a href="{{ route('journey.index') }}" class="flex items-center justify-center gap-1.5 w-full rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                            <span>ดูเส้นทางการอ่านของฉัน</span>
                            <span>→</span>
                        </a>
                    </div>
                </section>
            </div>
        </div>
    </div></div>
</x-app-layout>
