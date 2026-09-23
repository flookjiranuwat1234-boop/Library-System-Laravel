<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=noto-sans-thai:400,500,600,700&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-900 font-sans text-slate-800 antialiased">
        <div class="relative flex min-h-screen flex-col justify-between overflow-hidden bg-gradient-to-br from-emerald-100 via-teal-50 to-slate-100">
            <div class="pointer-events-none absolute -top-40 -left-40 h-96 w-96 rounded-full bg-emerald-400/20 blur-3xl"></div>
            <div class="pointer-events-none absolute top-1/2 right-0 h-96 w-96 rounded-full bg-teal-300/20 blur-3xl"></div>
            
            <header class="mx-auto flex w-full max-w-6xl items-center justify-between px-6 py-4 sm:py-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-600 p-2 text-white shadow-md shadow-emerald-200">
                        <svg class="h-full w-full" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                            <path d="M6.5 6H20"/>
                            <path d="M6.5 18H20"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-emerald-600 font-medium">ห้องสมุด</p>
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

            <main class="mx-auto my-auto grid w-full max-w-6xl items-center gap-8 px-6 py-4 sm:py-6 lg:grid-cols-2">
                <section class="space-y-6">
                    <div class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-100 px-3.5 py-1 text-xs font-semibold tracking-wider text-emerald-700">
                        <span class="flex h-2 w-2 rounded-full bg-emerald-500"></span>
                        ระบบบริหารจัดการคลังหนังสือ
                    </div>

                    <div class="space-y-3">
                        <h2 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                            ค้นหา ยืม และคืนหนังสือได้อย่างสะดวกสบาย
                        </h2>
                        <p class="max-w-xl text-base text-slate-600 leading-relaxed">
                            ยกระดับการยืมหนังสือด้วยระบบติดตามสถานะ วันกำหนดคืน และสถิติการอ่านแบบเรียลไทม์
                        </p>
                    </div>

                    <!-- Quick Highlights & Summary Bar -->
                    <div class="grid gap-3 sm:grid-cols-3">
                        <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-3 shadow-sm">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-600 text-white shadow-sm">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-slate-500 font-medium">หนังสือในคลัง</p>
                                <p class="text-sm font-bold text-slate-900 truncate">{{ $totalBooks }}+ เล่ม</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-3 shadow-sm">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-sky-500 text-white shadow-sm">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h10M7 12h10m-8 5h8" /></svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-slate-500 font-medium">หมวดหมู่</p>
                                <p class="text-sm font-bold text-slate-900 truncate">{{ $totalCategories }} หมวด</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-3 shadow-sm">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-500 text-white shadow-sm">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-slate-500 font-medium">เวลาทำการ</p>
                                <p class="text-sm font-bold text-slate-900 whitespace-nowrap">08:30-16:30 น.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="relative">
                    <div class="absolute -inset-6 rounded-[2rem] bg-emerald-300/20 blur-3xl"></div>
                    <div class="relative overflow-hidden rounded-[2rem] border border-slate-200 bg-white/90 p-5 sm:p-6 shadow-2xl shadow-slate-300/50 backdrop-blur">
                        <div class="mb-4 flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-500">ภาพรวมคลังหนังสือ</p>
                                <h3 class="mt-1 text-xl font-bold text-slate-900 sm:text-2xl">หนังสือแนะนำ</h3>
                            </div>
                            <span class="rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">ข้อมูลล่าสุด</span>
                        </div>

                        <div class="space-y-3">
                            @forelse ($featuredBooks->take(3) as $book)
                                <div class="flex items-center gap-3.5 rounded-2xl border border-slate-200 bg-slate-50/80 p-3 transition hover:border-emerald-200 hover:bg-emerald-50/50">
                                    <div class="flex h-12 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-amber-200 via-emerald-200 to-sky-200 text-lg text-slate-900 shadow-inner">
                                        📘
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-semibold text-slate-900">{{ $book->title }}</p>
                                        <p class="truncate text-xs text-slate-500">{{ $book->author }} · {{ $book->category->name ?? 'ทั่วไป' }}</p>
                                    </div>
                                    <span class="shrink-0 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700">เหลือ {{ $book->stock }} เล่ม</span>
                                </div>
                            @empty
                                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-500">
                                    คลังหนังสือพร้อมสำหรับหนังสือเล่มแรกแล้ว
                                </div>
                            @endforelse
                        </div>
                    </div>
                </section>
            </main>

            <footer class="py-2 text-center text-xs text-slate-400">
                © {{ now()->year }} {{ config('app.name') }}
            </footer>
        </div>
    </body>
</html>
