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
    <body class="font-sans antialiased">
        <div class="relative flex min-h-screen flex-col overflow-hidden bg-slate-50">
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_10%_10%,rgba(16,185,129,0.08),transparent_25%),radial-gradient(circle_at_90%_40%,rgba(14,165,233,0.07),transparent_25%)]"></div>
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="border-b border-slate-200 bg-white/90">
                    <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="relative flex-1">
                {{ $slot }}
            </main>

            <footer class="relative border-t border-slate-200/80 bg-white/80 py-6 backdrop-blur">
                <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                    <p>© {{ now()->year }} {{ config('app.name', 'ระบบห้องสมุด') }}</p>
                    <p class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>ระบบพร้อมให้บริการ</p>
                </div>
            </footer>
        </div>
    </body>
</html>
