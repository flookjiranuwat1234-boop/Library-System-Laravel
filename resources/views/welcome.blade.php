<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=noto-sans-thai:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-emerald-50 font-sans text-slate-800 antialiased">
        <div class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(16,185,129,0.15),transparent_32%),radial-gradient(circle_at_bottom_right,_rgba(14,165,233,0.1),transparent_35%),linear-gradient(135deg,#ecfdf5,#ffffff_48%,#f0f9ff)]">
            <header class="mx-auto flex max-w-6xl items-center justify-between px-6 py-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500 text-2xl shadow-lg shadow-emerald-200">📚</div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-emerald-600">ห้องสมุด</p>
                        <h1 class="text-xl font-bold text-slate-900">{{ config('app.name') }}</h1>
                    </div>
                </div>

                @if (Route::has('login'))
                    <nav class="flex items-center gap-3">
                        @auth
                        <a href="{{ url('/dashboard') }}" class="rounded-xl border border-slate-300 bg-white px-5 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-emerald-400 hover:text-emerald-700">แดชบอร์ด</a>
                        @else
                        <a href="{{ route('login') }}" class="rounded-xl border border-slate-300 bg-white px-5 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-emerald-400 hover:text-emerald-700">เข้าสู่ระบบ</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="rounded-xl bg-emerald-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">สมัครสมาชิก</a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </header>

            <main class="mx-auto grid max-w-6xl items-center gap-10 px-6 pb-16 pt-10 lg:grid-cols-2 lg:pt-16">
                <section class="space-y-7">
                    <div class="inline-flex rounded-full border border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-emerald-700">
                        ระบบห้องสมุดอัจฉริยะ
                    </div>

                    <div class="space-y-4">
                        <h2 class="text-4xl font-extrabold leading-tight text-slate-900 sm:text-5xl">
                            จัดการหนังสือ การยืม–คืน และประวัติทั้งหมดได้ในที่เดียว
                        </h2>
                        <p class="max-w-xl text-lg text-slate-600">
                            ค้นหาหนังสือได้ง่าย ติดตามวันครบกำหนดชัดเจน และตรวจสอบประวัติการยืมได้ทุกเวลา
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-4">
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="rounded-xl bg-emerald-600 px-6 py-3 text-base font-semibold text-white shadow-lg shadow-emerald-200 transition hover:bg-emerald-700">เข้าสู่ระบบ</a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rounded-xl border border-slate-300 bg-white px-6 py-3 text-base font-semibold text-slate-700 shadow-sm transition hover:border-emerald-400 hover:text-emerald-700">สร้างบัญชีใหม่</a>
                        @endif
                        <a href="{{ route('catalog.index') }}" class="rounded-xl border border-emerald-300 bg-emerald-50 px-6 py-3 text-base font-semibold text-emerald-700 shadow-sm transition hover:bg-emerald-100">📚 ดูหนังสือทั้งหมด</a>
                    </div>

                    <div class="grid max-w-lg gap-4 sm:grid-cols-3">
                        <div class="rounded-2xl border border-slate-200 bg-white/80 p-4 shadow-sm">
                            <div class="text-2xl font-bold text-slate-900">{{ $totalBooks }}</div>
                            <div class="mt-1 text-sm text-slate-500">หนังสือ</div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white/80 p-4 shadow-sm">
                            <div class="text-2xl font-bold text-slate-900">{{ $totalCategories }}</div>
                            <div class="mt-1 text-sm text-slate-500">หมวดหมู่</div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white/80 p-4 shadow-sm">
                            <div class="text-2xl font-bold text-slate-900">{{ $activeLoans }}</div>
                            <div class="mt-1 text-sm text-slate-500">กำลังยืม</div>
                        </div>
                    </div>
                </section>

                <section class="relative">
                    <div class="absolute -inset-6 rounded-[2rem] bg-emerald-300/20 blur-3xl"></div>
                    <div class="relative overflow-hidden rounded-[2rem] border border-slate-200 bg-white/90 p-6 shadow-2xl shadow-slate-300/50 backdrop-blur">
                        <div class="mb-6 flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-500">ภาพรวมคลังหนังสือ</p>
                                <h3 class="mt-2 text-2xl font-bold text-slate-900">หนังสือแนะนำ</h3>
                            </div>
                            <span class="rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">ข้อมูลล่าสุด</span>
                        </div>

                        <div class="space-y-4">
                            @forelse ($featuredBooks as $book)
                                <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-slate-50/80 p-4 transition hover:border-emerald-200 hover:bg-emerald-50/50">
                                    <div class="flex h-16 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-amber-200 via-emerald-200 to-sky-200 text-xl text-slate-900 shadow-inner">
                                        📘
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-base font-semibold text-slate-900">{{ $book->title }}</p>
                                    <p class="text-sm text-slate-500">{{ $book->author }} · {{ $book->category->name ?? 'ทั่วไป' }}</p>
                                    </div>
                                <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700">เหลือ {{ $book->stock }} เล่ม</span>
                                </div>
                            @empty
                                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center text-sm text-slate-500">
                                คลังหนังสือพร้อมสำหรับหนังสือเล่มแรกแล้ว
                                </div>
                            @endforelse
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>
