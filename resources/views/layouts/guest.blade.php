<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=noto-sans-thai:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased">
        <main class="relative min-h-screen overflow-hidden bg-gradient-to-br from-emerald-50 via-white to-sky-50 p-3 sm:p-6 lg:p-8">
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(16,185,129,0.14),_transparent_30%),radial-gradient(circle_at_bottom_right,_rgba(14,165,233,0.1),_transparent_35%)]"></div>
            <div class="relative mx-auto grid min-h-[calc(100vh-1.5rem)] max-w-6xl overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white shadow-2xl shadow-slate-300/50 sm:min-h-[calc(100vh-3rem)] lg:grid-cols-[1.05fr_0.95fr]">
                <section class="relative hidden overflow-hidden bg-gradient-to-br from-emerald-800 via-teal-800 to-slate-900 p-10 text-white lg:flex lg:flex-col lg:justify-between xl:p-14">
                    <div class="absolute -right-24 -top-24 h-80 w-80 rounded-full border-[56px] border-white/5"></div><div class="absolute -bottom-20 -left-20 h-72 w-72 rounded-full bg-emerald-400/10 blur-2xl"></div>
                    <a href="/" class="relative flex items-center gap-3"><span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-400 text-2xl shadow-lg shadow-emerald-950/30">📚</span><span><span class="block text-lg font-bold">{{ config('app.name', 'ระบบห้องสมุด') }}</span><span class="block text-xs text-emerald-100/70">คลังความรู้สำหรับทุกคน</span></span></a>
                    <div class="relative py-12"><span class="inline-flex rounded-full border border-emerald-300/20 bg-emerald-300/10 px-3 py-1 text-xs font-semibold text-emerald-200">เรียนรู้ได้ทุกวัน</span><h1 class="mt-5 max-w-md text-4xl font-bold leading-tight xl:text-5xl">หนังสือทุกเล่ม<br>เปิดประตูสู่โลกใบใหม่</h1><p class="mt-5 max-w-md leading-relaxed text-emerald-50/70">ค้นหา ยืม และติดตามหนังสือที่คุณสนใจได้ง่าย ๆ ในพื้นที่เดียว</p>
                        <div class="mt-8 grid max-w-md gap-3 sm:grid-cols-2"><div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur"><span class="text-2xl">🔎</span><p class="mt-2 text-sm font-semibold">ค้นหาได้รวดเร็ว</p><p class="mt-1 text-xs text-white/60">ค้นหาทันทีจากชื่อหรือผู้แต่ง</p></div><div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur"><span class="text-2xl">⏰</span><p class="mt-2 text-sm font-semibold">ไม่พลาดกำหนดคืน</p><p class="mt-1 text-xs text-white/60">ติดตามรายการยืมได้ทุกเวลา</p></div></div>
                    </div>
                    <p class="relative text-xs text-white/40">© {{ now()->year }} {{ config('app.name', 'ระบบห้องสมุด') }}</p>
                </section>

                <section class="flex min-h-full flex-col bg-white">
                    <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 lg:hidden"><a href="/" class="flex items-center gap-2 font-bold text-slate-900"><span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-500">📚</span>{{ config('app.name', 'ระบบห้องสมุด') }}</a><a href="/" class="text-sm font-medium text-slate-500">หน้าหลัก</a></div>
                    <div class="flex flex-1 items-center justify-center px-5 py-8 sm:px-10 lg:px-12 xl:px-16"><div class="w-full max-w-md">{{ $slot }}<a href="/" class="mt-8 flex items-center justify-center gap-2 text-sm text-slate-400 transition hover:text-emerald-700">← กลับสู่หน้าหลัก</a></div></div>
                </section>
            </div>
        </main>
    </body>
</html>
