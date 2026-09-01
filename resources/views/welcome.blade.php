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
    <body class="min-h-screen bg-slate-950 font-sans text-slate-100 antialiased">
        <div class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.18),transparent_40%),linear-gradient(135deg,#020817,#0f172a_40%,#111827)]">
            <header class="mx-auto flex max-w-6xl items-center justify-between px-6 py-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-500/15 text-2xl shadow-lg shadow-emerald-500/20">📚</div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-emerald-300">ห้องสมุด</p>
                        <h1 class="text-xl font-bold text-white">{{ config('app.name') }}</h1>
                    </div>
                </div>

                @if (Route::has('login'))
                    <nav class="flex items-center gap-3">
                        @auth
                        <a href="{{ url('/dashboard') }}" class="rounded-md border border-slate-700 bg-slate-800/80 px-5 py-2 text-sm font-medium text-white transition hover:border-emerald-400 hover:text-emerald-300">แดชบอร์ด</a>
                        @else
                        <a href="{{ route('login') }}" class="rounded-md border border-slate-700 bg-slate-800/80 px-5 py-2 text-sm font-medium text-slate-200 transition hover:border-sky-400 hover:text-sky-300">เข้าสู่ระบบ</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="rounded-md bg-emerald-500 px-5 py-2 text-sm font-semibold text-slate-950 transition hover:bg-emerald-400">สมัครสมาชิก</a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </header>

            <main class="mx-auto grid max-w-6xl items-center gap-10 px-6 pb-16 pt-10 lg:grid-cols-2 lg:pt-16">
                <section class="space-y-7">
                    <div class="inline-flex rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3 py-1 text-xs font-medium uppercase tracking-[0.25em] text-emerald-300">
                        ระบบห้องสมุดอัจฉริยะ
                    </div>

                    <div class="space-y-4">
                        <h2 class="text-4xl font-extrabold leading-tight text-white sm:text-5xl">
                            จัดการหนังสือ การยืม–คืน และประวัติทั้งหมดได้ในที่เดียว
                        </h2>
                        <p class="max-w-xl text-lg text-slate-300">
                            ค้นหาหนังสือได้ง่าย ติดตามวันครบกำหนดชัดเจน และตรวจสอบประวัติการยืมได้ทุกเวลา
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-4">
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="rounded-xl bg-emerald-500 px-6 py-3 text-base font-semibold text-slate-950 shadow-lg shadow-emerald-500/30 transition hover:bg-emerald-400">เข้าสู่ระบบ</a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rounded-xl border border-slate-600 bg-slate-900/70 px-6 py-3 text-base font-semibold text-white transition hover:border-sky-400 hover:text-sky-300">สร้างบัญชีใหม่</a>
                        @endif
                    </div>

                    <div class="grid max-w-lg gap-4 sm:grid-cols-3">
                        <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-4">
                            <div class="text-2xl font-bold text-white">{{ $totalBooks }}</div>
                            <div class="mt-1 text-sm text-slate-400">หนังสือ</div>
                        </div>
                        <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-4">
                            <div class="text-2xl font-bold text-white">{{ $totalCategories }}</div>
                            <div class="mt-1 text-sm text-slate-400">หมวดหมู่</div>
                        </div>
                        <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-4">
                            <div class="text-2xl font-bold text-white">{{ $activeLoans }}</div>
                            <div class="mt-1 text-sm text-slate-400">กำลังยืม</div>
                        </div>
                    </div>
                </section>

                <section class="relative">
                    <div class="absolute -inset-6 rounded-[2rem] bg-emerald-500/10 blur-3xl"></div>
                    <div class="relative overflow-hidden rounded-[2rem] border border-slate-700 bg-slate-900/80 p-6 shadow-2xl shadow-slate-950/50">
                        <div class="mb-6 flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">ภาพรวมคลังหนังสือ</p>
                                <h3 class="mt-2 text-2xl font-bold text-white">หนังสือแนะนำ</h3>
                            </div>
                            <span class="rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3 py-1 text-xs font-medium text-emerald-300">ข้อมูลล่าสุด</span>
                        </div>

                        <div class="space-y-4">
                            @forelse ($featuredBooks as $book)
                                <div class="flex items-center gap-4 rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
                                    <div class="flex h-16 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-amber-200 via-emerald-200 to-sky-200 text-xl text-slate-900 shadow-inner">
                                        📘
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-base font-semibold text-white">{{ $book->title }}</p>
                                    <p class="text-sm text-slate-400">{{ $book->author }} · {{ $book->category->name ?? 'ทั่วไป' }}</p>
                                    </div>
                                <span class="rounded-full bg-emerald-500/10 px-2.5 py-1 text-xs font-medium text-emerald-300">เหลือ {{ $book->stock }} เล่ม</span>
                                </div>
                            @empty
                                <div class="rounded-2xl border border-dashed border-slate-700 bg-slate-950/50 p-8 text-center text-sm text-slate-400">
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
