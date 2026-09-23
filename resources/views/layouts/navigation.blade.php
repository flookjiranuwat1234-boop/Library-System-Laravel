<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-white/10 bg-slate-950/95 text-white shadow-lg shadow-slate-950/10 backdrop-blur">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex">
                <!-- Logo -->
                <div class="flex shrink-0 items-center">
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="flex items-center gap-2.5 font-bold tracking-tight text-white">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-500 text-white shadow-sm">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </span>
                        <span class="hidden lg:block">{{ config('app.name', 'ระบบห้องสมุด') }}</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:ms-8 sm:flex sm:items-center sm:gap-1">
                    @if(Auth::user()->role === 'admin')
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Admin Dashboard') }}
                    </x-nav-link>
                    @else
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @endif
                    <x-nav-link :href="route('books.index')" :active="request()->routeIs('books.*') || request()->routeIs('categories.*')">
                        {{ __('หนังสือ & หมวดหมู่') }}
                    </x-nav-link>
                    <x-nav-link :href="route('borrows.index')" :active="request()->routeIs('borrows.*')">
                        {{ Auth::user()->role === 'admin' ? __('Borrow Records') : __('My Borrows') }}
                    </x-nav-link>
                    @if(Auth::user()->role === 'admin')
                    <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        {{ __('จัดการสมาชิก') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.journey.index')" :active="request()->routeIs('admin.journey.*') || request()->routeIs('admin.badges.*')">
                        {{ __('สถิติ & เหรียญรางวัล') }}
                    </x-nav-link>
                    @else
                    <x-nav-link :href="route('journey.index')" :active="request()->routeIs('journey.*')">
                        {{ __('เส้นทางการอ่าน') }}
                    </x-nav-link>
                    @endif
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

                            @if(Auth::user()->role === 'admin' && $unreadAdminNotificationsCount > 0)
                                <span class="inline-flex min-w-5 items-center justify-center rounded-full bg-rose-500 px-1.5 py-0.5 text-xs font-bold text-white" aria-label="มีการแจ้งเตือนที่ยังไม่อ่าน {{ $unreadAdminNotificationsCount }} รายการ">{{ $unreadAdminNotificationsCount > 99 ? '99+' : $unreadAdminNotificationsCount }}</span>
                            @endif

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
                        @if(Auth::user()->role === 'admin')
                            <x-dropdown-link :href="route('admin.notifications.index')">การแจ้งเตือน{{ $unreadAdminNotificationsCount > 0 ? ' ('.$unreadAdminNotificationsCount.' รายการใหม่)' : '' }}</x-dropdown-link>
                            <x-dropdown-link :href="route('admin.reports.index')">รายงาน</x-dropdown-link>
                            <x-dropdown-link :href="route('admin.activity-logs.index')">บันทึกกิจกรรม</x-dropdown-link>
                            <x-dropdown-link :href="route('admin.settings.edit')">ตั้งค่าระบบ</x-dropdown-link>
                        @endif

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
            @if(Auth::user()->role === 'admin')
            <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                {{ __('Admin Dashboard') }}
            </x-responsive-nav-link>
            @else
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @endif
            <x-responsive-nav-link :href="route('books.index')" :active="request()->routeIs('books.*') || request()->routeIs('categories.*')">
                {{ __('หนังสือ & หมวดหมู่') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('borrows.index')" :active="request()->routeIs('borrows.*')">
                {{ Auth::user()->role === 'admin' ? __('Borrow Records') : __('My Borrows') }}
            </x-responsive-nav-link>
            @if(Auth::user()->role !== 'admin')
            <x-responsive-nav-link :href="route('journey.index')" :active="request()->routeIs('journey.*')">
                {{ __('เส้นทางการอ่าน') }}
            </x-responsive-nav-link>
            @endif
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

                @if(Auth::user()->role === 'admin')
                    <x-responsive-nav-link :href="route('admin.notifications.index')">
                        การแจ้งเตือน{{ $unreadAdminNotificationsCount > 0 ? ' ('.$unreadAdminNotificationsCount.' รายการใหม่)' : '' }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.reports.index')">รายงาน</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.activity-logs.index')">บันทึกกิจกรรม</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.settings.edit')">ตั้งค่าระบบ</x-responsive-nav-link>
                @endif

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
