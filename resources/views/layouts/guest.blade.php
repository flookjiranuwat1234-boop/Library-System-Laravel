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
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased">
        <div class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden bg-slate-950 px-4 py-10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(16,185,129,0.18),_transparent_35%),radial-gradient(circle_at_bottom_right,_rgba(14,165,233,0.12),_transparent_35%)]"></div>
            <a href="/" class="relative mb-7 flex items-center gap-3 text-white transition hover:text-emerald-300">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-500 text-2xl shadow-lg shadow-emerald-950/30">📚</span>
                <span>
                    <span class="block text-lg font-bold">{{ config('app.name', 'ระบบห้องสมุด') }}</span>
                    <span class="block text-xs text-slate-400">คลังความรู้สำหรับทุกคน</span>
                </span>
            </a>

            <div class="relative w-full overflow-hidden rounded-3xl border border-white/10 bg-white p-6 shadow-2xl shadow-slate-950/40 sm:max-w-md sm:p-8">
                {{ $slot }}
            </div>
            <a href="/" class="relative mt-6 text-sm text-slate-400 transition hover:text-white">← กลับสู่หน้าหลัก</a>
        </div>
    </body>
</html>
