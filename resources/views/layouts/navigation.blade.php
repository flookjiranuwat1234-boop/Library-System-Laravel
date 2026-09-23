@if(Auth::user()->role === 'admin')
<!-- Admin Desktop Sidebar (Fixed Left) -->
<aside class="hidden lg:fixed lg:inset-y-0 lg:z-40 lg:flex lg:w-64 lg:flex-col lg:border-r lg:border-slate-800/80 lg:bg-slate-950 lg:text-white shadow-2xl">
    <!-- Sidebar Header / Logo & Notifications -->
    <div class="flex h-16 shrink-0 items-center justify-between border-b border-white/10 px-5">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 font-bold tracking-tight text-white group">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white shadow-md shadow-emerald-950/50 transition group-hover:scale-105">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </span>
            <div class="flex flex-col">
                <span class="text-sm font-extrabold leading-none text-white">{{ config('app.name', 'ระบบห้องสมุด') }}</span>
                <span class="text-[10px] font-medium text-emerald-400/80 mt-0.5">Admin Management</span>
            </div>
        </a>

        <a href="{{ route('admin.notifications.index') }}" class="relative rounded-xl border border-white/10 bg-white/5 p-2 text-slate-300 hover:bg-white/10 hover:text-white transition" title="การแจ้งเตือน">
            <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            @if($unreadAdminNotificationsCount > 0)
                <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[9px] font-bold text-white shadow-sm animate-pulse">{{ $unreadAdminNotificationsCount > 99 ? '99+' : $unreadAdminNotificationsCount }}</span>
            @endif
        </a>
    </div>

    <!-- Sidebar Scrollable Navigation Content -->
    <div class="flex flex-1 flex-col overflow-y-auto px-3 py-4 space-y-6">
        <!-- Main Menu Section -->
        <div>
            <div class="px-3 mb-2 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                เมนูหลัก
            </div>
            <nav class="space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-xs font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 00-1 1m-6 0h6"/></svg>
                    <span>{{ __('แดชบอร์ดผู้ดูแล') }}</span>
                </a>

                <a href="{{ route('books.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-xs font-semibold transition {{ request()->routeIs('books.*') || request()->routeIs('categories.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>{{ __('หนังสือ & หมวดหมู่') }}</span>
                </a>

                <a href="{{ route('borrows.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-xs font-semibold transition {{ request()->routeIs('borrows.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <span>{{ __('ประวัติการยืม') }}</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-xs font-semibold transition {{ request()->routeIs('admin.users.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>{{ __('จัดการสมาชิก') }}</span>
                </a>

                <a href="{{ route('admin.journey.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-xs font-semibold transition {{ request()->routeIs('admin.journey.*') || request()->routeIs('admin.badges.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    <span>{{ __('สถิติ & เหรียญรางวัล') }}</span>
                </a>
            </nav>
        </div>

        <!-- Admin Extras Section -->
        <div>
            <div class="px-3 mb-2 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                การจัดการระบบ
            </div>
            <nav class="space-y-1">
                <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('admin.reports.*') ? 'bg-white/15 text-white' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>รายงานสรุป</span>
                </a>
                <a href="{{ route('admin.activity-logs.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('admin.activity-logs.*') ? 'bg-white/15 text-white' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>บันทึกกิจกรรม</span>
                </a>
                <a href="{{ route('admin.settings.edit') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('admin.settings.*') ? 'bg-white/15 text-white' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>ตั้งค่าระบบ</span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Sidebar Footer / QR Scan Button & User Info -->
    <div class="mt-auto border-t border-white/10 p-3 space-y-3 bg-slate-950">
        <button @click="$dispatch('open-qr-scanner')" type="button" class="w-full flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 px-3.5 py-2.5 text-xs font-bold text-white shadow-lg shadow-emerald-950/40 transition hover:from-emerald-500 hover:to-teal-500">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            <span>สแกน QR Code</span>
        </button>

        <div class="flex items-center justify-between rounded-xl bg-white/5 p-2.5 border border-white/5">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 min-w-0 hover:opacity-80 transition group" title="แก้ไขโปรไฟล์">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-500/20 text-xs font-bold text-emerald-300 border border-emerald-500/30 group-hover:bg-emerald-500/30">
                    {{ mb_substr(Auth::user()->name, 0, 1) }}
                </span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-xs font-bold text-white group-hover:text-emerald-300 transition">{{ Auth::user()->name }}</p>
                    <p class="truncate text-[10px] text-slate-400">ผู้ดูแลระบบ</p>
                </div>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                @csrf
                <button type="submit" title="ออกจากระบบ" class="rounded-lg p-1.5 text-slate-400 hover:bg-rose-500/20 hover:text-rose-300 transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- Admin Mobile Slide-over Drawer -->
<div x-show="sidebarOpen" x-cloak class="relative z-50 lg:hidden" role="dialog" aria-modal="true">
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition-opacity ease-linear duration-300" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm"></div>

    <div class="fixed inset-0 flex">
        <div x-show="sidebarOpen" 
             x-transition:enter="transition ease-in-out duration-300 transform" 
             x-transition:enter-start="-translate-x-full" 
             x-transition:enter-end="translate-x-0" 
             x-transition:leave="transition ease-in-out duration-300 transform" 
             x-transition:leave-start="translate-x-0" 
             x-transition:leave-end="-translate-x-full" 
             class="relative flex w-full max-w-xs flex-1 flex-col bg-slate-950 text-white pb-4 pt-5 shadow-2xl">
            
            <div class="absolute right-2 top-2 p-1">
                <button type="button" @click="sidebarOpen = false" class="rounded-xl p-2 text-slate-400 hover:bg-white/10 hover:text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Mobile Brand Header -->
            <div class="flex shrink-0 items-center gap-3 px-5 pb-4 border-b border-white/10">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white shadow-md">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </span>
                <span class="text-sm font-extrabold text-white">{{ config('app.name', 'ระบบห้องสมุด') }}</span>
            </div>

            <div class="mt-4 flex-1 overflow-y-auto px-3 space-y-4">
                <nav class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-xs font-semibold {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600 text-white' : 'text-slate-300 hover:bg-white/10' }}">
                        <span>{{ __('แดชบอร์ดผู้ดูแล') }}</span>
                    </a>

                    <a href="{{ route('books.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-xs font-semibold {{ request()->routeIs('books.*') || request()->routeIs('categories.*') ? 'bg-emerald-600 text-white' : 'text-slate-300 hover:bg-white/10' }}">
                        <span>{{ __('หนังสือ & หมวดหมู่') }}</span>
                    </a>

                    <a href="{{ route('borrows.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-xs font-semibold {{ request()->routeIs('borrows.*') ? 'bg-emerald-600 text-white' : 'text-slate-300 hover:bg-white/10' }}">
                        <span>{{ __('ประวัติการยืม') }}</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-xs font-semibold {{ request()->routeIs('admin.users.*') ? 'bg-emerald-600 text-white' : 'text-slate-300 hover:bg-white/10' }}">
                        <span>{{ __('จัดการสมาชิก') }}</span>
                    </a>

                    <a href="{{ route('admin.journey.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-xs font-semibold {{ request()->routeIs('admin.journey.*') || request()->routeIs('admin.badges.*') ? 'bg-emerald-600 text-white' : 'text-slate-300 hover:bg-white/10' }}">
                        <span>{{ __('สถิติ & เหรียญรางวัล') }}</span>
                    </a>
                </nav>

                <div class="pt-3 border-t border-white/10">
                    <p class="px-3 mb-2 text-[10px] font-bold text-slate-400 uppercase">การจัดการระบบ</p>
                    <nav class="space-y-1">
                        <a href="{{ route('admin.reports.index') }}" class="block rounded-xl px-3 py-2 text-xs font-medium text-slate-300 hover:bg-white/10">รายงานสรุป</a>
                        <a href="{{ route('admin.activity-logs.index') }}" class="block rounded-xl px-3 py-2 text-xs font-medium text-slate-300 hover:bg-white/10">บันทึกกิจกรรม</a>
                        <a href="{{ route('admin.settings.edit') }}" class="block rounded-xl px-3 py-2 text-xs font-medium text-slate-300 hover:bg-white/10">ตั้งค่าระบบ</a>
                    </nav>
                </div>
            </div>

            <div class="p-3 border-t border-white/10">
                <button @click="$dispatch('open-qr-scanner'); sidebarOpen = false" type="button" class="w-full flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-3.5 py-2.5 text-xs font-bold text-white shadow-md">
                    📷 <span>สแกน QR Code</span>
                </button>
            </div>
        </div>
    </div>
</div>
@else
<!-- Regular Member Original Top Horizontal Navbar -->
<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-white/10 bg-slate-950/95 text-white shadow-lg shadow-slate-950/10 backdrop-blur">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex">
                <!-- Logo -->
                <div class="flex shrink-0 items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 font-bold tracking-tight text-white">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-500 text-white shadow-sm">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </span>
                        <span class="hidden lg:block">{{ config('app.name', 'ระบบห้องสมุด') }}</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:ms-8 sm:flex sm:items-center sm:gap-1">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('แดชบอร์ด') }}
                    </x-nav-link>
                    <x-nav-link :href="route('books.index')" :active="request()->routeIs('books.*') || request()->routeIs('categories.*')">
                        {{ __('หนังสือ & หมวดหมู่') }}
                    </x-nav-link>
                    <x-nav-link :href="route('borrows.index')" :active="request()->routeIs('borrows.*')">
                        {{ __('รายการยืมของฉัน') }}
                    </x-nav-link>
                    <x-nav-link :href="route('journey.index')" :active="request()->routeIs('journey.*')">
                        {{ __('เส้นทางการอ่าน') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- QR Scanner & Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:gap-3 sm:ms-6">
                <button @click="$dispatch('open-qr-scanner')" type="button" class="inline-flex items-center gap-1.5 rounded-xl border border-emerald-500/40 bg-emerald-500/15 px-3 py-1.5 text-xs font-bold text-emerald-300 transition hover:bg-emerald-500/30 hover:text-white" title="เปิดกล้องสแกน QR Code หนังสือ">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    <span>สแกน QR</span>
                </button>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="relative inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm font-medium text-slate-200 transition hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-emerald-400">
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-500/20 text-xs font-bold text-emerald-300">{{ mb_substr(Auth::user()->name, 0, 1) }}</span>
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center rounded-xl p-2 text-slate-300 transition hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-emerald-400">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-white/10 bg-slate-950 sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('แดชบอร์ด') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('books.index')" :active="request()->routeIs('books.*') || request()->routeIs('categories.*')">
                {{ __('หนังสือ & หมวดหมู่') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('borrows.index')" :active="request()->routeIs('borrows.*')">
                {{ __('รายการยืมของฉัน') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('journey.index')" :active="request()->routeIs('journey.*')">
                {{ __('เส้นทางการอ่าน') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="border-t border-white/10 pb-3 pt-4">
            <div class="px-4 mb-3">
                <button @click="$dispatch('open-qr-scanner'); open = false" type="button" class="w-full flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-emerald-700">
                    📷 <span>เปิดกล้องสแกน QR Code</span>
                </button>
            </div>
            <div class="px-4">
                <div class="text-base font-semibold text-white">{{ Auth::user()->name }}</div>
                <div class="text-sm text-slate-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
@endif
