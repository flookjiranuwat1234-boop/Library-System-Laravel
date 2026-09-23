<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Executive Overview</p>
                <h2 class="page-title text-slate-900">แดชบอร์ดผู้ดูแลระบบ</h2>
                <p class="text-xs text-slate-500 mt-0.5">สรุปภาพรวมระบบห้องสมุด สถิติการยืม-คืน และกิจกรรมล่าสุด</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    ระบบพร้อมใช้งาน
                </span>
                <span class="text-xs text-slate-400 font-mono">{{ now()->locale('th')->translatedFormat('j M Y, H:i') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="page-shell">
        <div class="page-container space-y-8">

            {{-- Hero Greeting Banner --}}
            <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-800 via-teal-800 to-slate-900 p-6 text-white shadow-xl sm:p-8">
                <div class="absolute -right-16 -top-20 h-64 w-64 rounded-full border-[48px] border-white/5"></div>
                <div class="absolute -bottom-20 -left-20 h-56 w-56 rounded-full bg-emerald-400/10 blur-3xl"></div>
                
                <div class="relative z-10 flex flex-col justify-between gap-6 lg:flex-row lg:items-center">
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-2 rounded-full border border-emerald-300/20 bg-emerald-400/10 px-3 py-1 text-xs font-medium text-emerald-200 backdrop-blur">
                            <span>ห้องสมุดออนไลน์</span>
                            <span>•</span>
                            <span>โหมดผู้ดูแลระบบ</span>
                        </div>
                        <h1 class="text-2xl font-bold tracking-tight sm:text-3xl lg:text-4xl">
                            ยินดีต้อนรับกลับ, {{ Auth::user()->name }}
                        </h1>
                        <p class="max-w-xl text-sm leading-relaxed text-emerald-100/80">
                            บริหารจัดการทรัพยากรห้องสมุด ตรวจสอบสถานะการยืม-คืน และติดตามสถิตินักอ่านได้แบบเรียลไทม์ที่นี่
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3 sm:flex-nowrap">
                        <a href="{{ route('books.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-emerald-400 px-5 py-3 text-sm font-bold text-slate-950 shadow-lg shadow-emerald-950/40 transition hover:bg-emerald-300 hover:scale-[1.02]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            เพิ่มหนังสือใหม่
                        </a>
                        <a href="{{ route('borrows.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/20 bg-white/10 px-5 py-3 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/20">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            จัดการยืม–คืน
                        </a>
                    </div>
                </div>
            </section>

            {{-- Stats Overview Cards Grid --}}
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                <!-- Total Books -->
                <div class="group relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-sky-300 hover:shadow-lg hover:shadow-sky-500/5">
                    <div class="flex items-center justify-between">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50 text-sky-600 group-hover:bg-sky-500 group-hover:text-white transition duration-300">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total</span>
                    </div>
                    <div class="mt-4">
                        <p class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">{{ number_format($totalBooks) }}</p>
                        <p class="mt-0.5 text-xs font-medium text-slate-500">หนังสือทั้งหมด</p>
                    </div>
                </div>

                <!-- Available Books -->
                <div class="group relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-300 hover:shadow-lg hover:shadow-emerald-500/5">
                    <div class="flex items-center justify-between">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-500 group-hover:text-white transition duration-300">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Available</span>
                    </div>
                    <div class="mt-4">
                        <p class="text-2xl font-extrabold tracking-tight text-emerald-600 sm:text-3xl">{{ number_format($availableBooks) }}</p>
                        <p class="mt-0.5 text-xs font-medium text-slate-500">พร้อมให้ยืม</p>
                    </div>
                </div>

                <!-- Active Loans -->
                <div class="group relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-indigo-300 hover:shadow-lg hover:shadow-indigo-500/5">
                    <div class="flex items-center justify-between">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 group-hover:bg-indigo-500 group-hover:text-white transition duration-300">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600">Active</span>
                    </div>
                    <div class="mt-4">
                        <p class="text-2xl font-extrabold tracking-tight text-indigo-600 sm:text-3xl">{{ number_format($activeLoans) }}</p>
                        <p class="mt-0.5 text-xs font-medium text-slate-500">กำลังยืมอยู่</p>
                    </div>
                </div>

                <!-- Overdue Books -->
                <div class="group relative overflow-hidden rounded-2xl border {{ $overdueLoans > 0 ? 'border-rose-200 bg-rose-50/40' : 'border-slate-200/80 bg-white' }} p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-rose-300 hover:shadow-lg hover:shadow-rose-500/5">
                    <div class="flex items-center justify-between">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl {{ $overdueLoans > 0 ? 'bg-rose-100 text-rose-600 animate-pulse' : 'bg-rose-50 text-rose-600' }} group-hover:bg-rose-500 group-hover:text-white transition duration-300">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-rose-600">Overdue</span>
                    </div>
                    <div class="mt-4">
                        <p class="text-2xl font-extrabold tracking-tight text-rose-600 sm:text-3xl">{{ number_format($overdueLoans) }}</p>
                        <p class="mt-0.5 text-xs font-medium text-slate-500">เกินกำหนดคืน</p>
                    </div>
                </div>

                <!-- Total Members -->
                <div class="group relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-purple-300 hover:shadow-lg hover:shadow-purple-500/5">
                    <div class="flex items-center justify-between">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 text-purple-600 group-hover:bg-purple-500 group-hover:text-white transition duration-300">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-purple-600">Members</span>
                    </div>
                    <div class="mt-4">
                        <p class="text-2xl font-extrabold tracking-tight text-purple-600 sm:text-3xl">{{ number_format($totalMembers) }}</p>
                        <p class="mt-0.5 text-xs font-medium text-slate-500">สมาชิกทั้งหมด</p>
                    </div>
                </div>

                <!-- Categories -->
                <div class="group relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-amber-300 hover:shadow-lg hover:shadow-amber-500/5">
                    <div class="flex items-center justify-between">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition duration-300">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M11 7h8M11 11h8M11 15h8"/></svg>
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-amber-600">Topics</span>
                    </div>
                    <div class="mt-4">
                        <p class="text-2xl font-extrabold tracking-tight text-amber-600 sm:text-3xl">{{ number_format($totalCategories) }}</p>
                        <p class="mt-0.5 text-xs font-medium text-slate-500">หมวดหมู่ทั้งหมด</p>
                    </div>
                </div>
            </div>

            {{-- Popular Books Showcase & Top Categories Section (REPLACED QUICK ACTIONS) --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Popular Books Showcase (2 columns) -->
                <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm lg:col-span-2">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-sm">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            </span>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">หนังสือยอดนิยมที่มีการยืมสูงสุด</h3>
                                <p class="text-xs text-slate-500">สถิติหนังสือที่สมาชิกนิยมอ่านมากที่สุด</p>
                            </div>
                        </div>
                        <a href="{{ route('books.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-800">ดูทั้งหมด →</a>
                    </div>
                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        @forelse($popularBooks as $book)
                            <div class="group flex flex-col overflow-hidden rounded-2xl border border-slate-100 bg-slate-50/50 p-3 transition hover:border-emerald-200 hover:bg-white hover:shadow-md">
                                <div class="relative aspect-[4/5] overflow-hidden rounded-xl bg-slate-200">
                                    @if($book->coverImageUrl())
                                        <img src="{{ $book->coverImageUrl() }}" alt="{{ $book->title }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                    @else
                                        <div class="flex h-full flex-col items-center justify-center text-slate-400">
                                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                        </div>
                                    @endif
                                    <span class="absolute left-2 top-2 rounded-full bg-slate-900/80 px-2 py-0.5 text-[10px] font-bold text-amber-300 backdrop-blur">
                                        ยืม {{ $book->borrow_records_count }} ครั้ง
                                    </span>
                                </div>
                                <div class="mt-3 flex flex-1 flex-col">
                                    <span class="text-[10px] font-semibold uppercase text-emerald-600">{{ $book->category->name ?? 'ทั่วไป' }}</span>
                                    <h4 class="mt-0.5 line-clamp-1 text-xs font-bold text-slate-900 group-hover:text-emerald-700">{{ $book->title }}</h4>
                                    <p class="mt-0.5 truncate text-[11px] text-slate-500">{{ $book->author }}</p>
                                    <div class="mt-auto pt-2 flex items-center justify-between">
                                        <span class="text-[10px] font-semibold text-slate-400">คงเหลือ {{ $book->stock }} เล่ม</span>
                                        <a href="{{ route('books.show', $book) }}" class="text-[11px] font-bold text-emerald-600 hover:underline">ดู →</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-8 text-center text-xs text-slate-400">ยังไม่มีข้อมูลสถิติการยืม</div>
                        @endforelse
                    </div>
                </div>

                <!-- Top Categories Progress Card (1 column) -->
                <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-600 text-white shadow-sm">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </span>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">สัดส่วนหมวดหมู่</h3>
                                <p class="text-xs text-slate-500">หมวดหมู่ที่มีหนังสือมากที่สุด</p>
                            </div>
                        </div>
                        <a href="{{ route('categories.index') }}" class="text-xs font-semibold text-teal-600 hover:text-teal-800">จัดการ →</a>
                    </div>
                    <div class="mt-4 space-y-4">
                        @forelse($topCategories as $cat)
                            @php
                                $percent = $totalBooks > 0 ? round(($cat->books_count / $totalBooks) * 100) : 0;
                            @endphp
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-slate-700">🏷️ {{ $cat->name }}</span>
                                    <span class="font-semibold text-slate-500">{{ $cat->books_count }} เล่ม ({{ $percent }}%)</span>
                                </div>
                                <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-400 transition-all duration-500" style="width: {{ max($percent, 8) }}%"></div>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-xs text-slate-400">ยังไม่มีหมวดหมู่</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Alerts Section: Low Stock & Overdue Books --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Low Stock Alert -->
                <div class="overflow-hidden rounded-3xl border border-amber-200/90 bg-gradient-to-br from-amber-50/80 via-white to-orange-50/40 p-6 shadow-sm">
                    <div class="flex items-center justify-between border-b border-amber-100 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500 text-white shadow-sm">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </span>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">หนังสือใกล้หมดคลัง</h3>
                                <p class="text-xs text-slate-500">จำกัดจำนวนคงเหลือที่เหลือน้อย</p>
                            </div>
                        </div>
                        <a href="{{ route('books.index') }}" class="rounded-xl border border-amber-300 bg-white px-3 py-1.5 text-xs font-bold text-amber-800 shadow-sm transition hover:bg-amber-100">
                            ดูหนังสือทั้งหมด →
                        </a>
                    </div>
                    <div class="mt-4 space-y-3">
                        @forelse($lowStockBooks as $book)
                            <div class="flex items-center justify-between rounded-2xl border border-amber-100 bg-white p-3.5 shadow-sm transition hover:border-amber-300">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-bold text-slate-900">{{ $book->title }}</p>
                                        <p class="truncate text-xs text-slate-500">{{ $book->author }}</p>
                                    </div>
                                </div>
                                <span class="shrink-0 rounded-full border border-amber-300 bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">
                                    เหลือ {{ $book->stock }} เล่ม
                                </span>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-amber-200 bg-white/60 p-6 text-center text-xs text-slate-500">
                                สต็อกหนังสือทุกรายการเพียงพอ
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Overdue Books Alert -->
                <div class="overflow-hidden rounded-3xl border border-rose-200/90 bg-gradient-to-br from-rose-50/80 via-white to-red-50/40 p-6 shadow-sm">
                    <div class="flex items-center justify-between border-b border-rose-100 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-600 text-white shadow-sm">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">รายการเกินกำหนดคืน</h3>
                                <p class="text-xs text-slate-500">จำเป็นต้องติดตามและคืนหนังสือ</p>
                            </div>
                        </div>
                        <a href="{{ route('borrows.index') }}" class="rounded-xl border border-rose-300 bg-white px-3 py-1.5 text-xs font-bold text-rose-700 shadow-sm transition hover:bg-rose-100">
                            จัดการการยืม–คืน →
                        </a>
                    </div>
                    <div class="mt-4 space-y-3">
                        @forelse($overdueBooks->take(4) as $record)
                            <div class="flex items-center justify-between rounded-2xl border border-rose-100 bg-white p-3.5 shadow-sm transition hover:border-rose-300">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-rose-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-bold text-slate-900">{{ $record->book->title }}</p>
                                        <p class="truncate text-xs text-slate-500">ยืมโดย: <span class="font-semibold text-slate-700">{{ $record->user->name }}</span></p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="inline-block rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-bold text-rose-700">
                                        เกิน {{ (int) $record->due_date->diffInDays(now()) }} วัน
                                    </span>
                                    <p class="mt-0.5 text-[10px] text-slate-400">กำหนด: {{ $record->due_date->locale('th')->translatedFormat('j M Y') }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-rose-200 bg-white/60 p-6 text-center text-xs text-slate-500">
                                ไม่มีรายการหนังสือเกินกำหนดในขณะนี้
                            </div>
                        @endforelse

                        @if($overdueBooks->count() > 4)
                            <div class="text-center pt-1">
                                <span class="text-xs font-semibold text-rose-600">และอีก {{ $overdueBooks->count() - 4 }} รายการที่ต้องติดตาม</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Recent Borrows & Returns Activity Feeds --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Recent Borrows -->
                <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 font-bold">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                            </span>
                            <h3 class="text-base font-bold text-slate-900">ประวัติการยืมล่าสุด</h3>
                        </div>
                        <a href="{{ route('borrows.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">ดูทั้งหมด →</a>
                    </div>
                    <div class="mt-4 divide-y divide-slate-100">
                        @forelse($recentBorrows as $borrow)
                            <div class="flex items-center justify-between py-3.5 transition hover:bg-slate-50/80 px-2 rounded-xl">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700">
                                        {{ mb_substr($borrow->user->name, 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-bold text-slate-900">{{ $borrow->book->title }}</p>
                                        <p class="truncate text-xs text-slate-500">{{ $borrow->user->name }} • {{ $borrow->borrowed_at->locale('th')->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <span class="shrink-0 rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700 border border-indigo-100">
                                    กำลังยืม
                                </span>
                            </div>
                        @empty
                            <div class="py-8 text-center text-xs text-slate-400">ยังไม่มีข้อมูลการยืมล่าสุด</div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Returns -->
                <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 font-bold">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            </span>
                            <h3 class="text-base font-bold text-slate-900">ประวัติการคืนล่าสุด</h3>
                        </div>
                        <a href="{{ route('borrows.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-800">ดูทั้งหมด →</a>
                    </div>
                    <div class="mt-4 divide-y divide-slate-100">
                        @forelse($recentReturns as $activity)
                            <div class="flex items-center justify-between py-3.5 transition hover:bg-slate-50/80 px-2 rounded-xl">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-700">
                                        {{ mb_substr($activity->user->name, 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-bold text-slate-900">{{ $activity->book->title }}</p>
                                        <p class="truncate text-xs text-slate-500">{{ $activity->user->name }} • {{ $activity->returned_at?->locale('th')->diffForHumans() ?? 'คืนแล้ว' }}</p>
                                    </div>
                                </div>
                                <span class="shrink-0 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-100">
                                    คืนเรียบร้อย
                                </span>
                            </div>
                        @empty
                            <div class="py-8 text-center text-xs text-slate-400">ยังไม่มีข้อมูลการคืนล่าสุด</div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
