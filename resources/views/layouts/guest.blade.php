@props(['mode' => 'login'])

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
        <style>
            [x-cloak] { display: none !important; }
            ::-webkit-scrollbar { display: none !important; width: 0 !important; height: 0 !important; background: transparent !important; }
            * { -ms-overflow-style: none !important; scrollbar-width: none !important; }
        </style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased" x-data="{ activeTab: '{{ $mode }}' }">
        <main class="relative flex min-h-screen items-center justify-center overflow-hidden bg-gradient-to-br from-emerald-100 via-teal-50 to-slate-100 p-4 sm:p-6">
            <div class="pointer-events-none absolute -top-40 -left-40 h-96 w-96 rounded-full bg-emerald-400/20 blur-3xl"></div>
            <div class="pointer-events-none absolute top-1/2 right-0 h-96 w-96 rounded-full bg-teal-300/20 blur-3xl"></div>

            <!-- Main Container -->
            <div class="relative min-h-[500px] w-full max-w-4xl overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-2xl shadow-slate-300/40">
                
                <!-- Sliding Green Branding Panel (Overlay Card) -->
                <div class="absolute inset-y-0 z-20 hidden w-1/2 overflow-hidden bg-gradient-to-br from-emerald-600 via-teal-600 to-emerald-800 p-8 text-white transition-transform duration-700 ease-in-out lg:flex lg:flex-col lg:justify-between"
                     :class="activeTab === 'login' ? 'translate-x-0 rounded-r-3xl' : 'translate-x-full rounded-l-3xl'">
                    <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full border-[48px] border-white/10"></div>
                    <div class="absolute -bottom-20 -left-20 h-64 w-64 rounded-full bg-teal-300/20 blur-2xl"></div>

                    <!-- Header Brand Logo -->
                    <a href="/" class="relative flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-tr from-emerald-400 to-teal-300 p-2 shadow-md shadow-emerald-950/40">
                            <svg class="h-full w-full text-slate-950" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                                <path d="M6.5 6H20"/>
                                <path d="M6.5 18H20"/>
                            </svg>
                        </div>
                        <span><span class="block text-base font-bold">{{ config('app.name', 'ระบบห้องสมุด') }}</span><span class="block text-[11px] text-emerald-100/70">คลังความรู้สำหรับทุกคน</span></span>
                    </a>

                    <!-- Banner Content (Swaps with Tab) -->
                    <div class="relative py-4">
                        <!-- Login Banner Content -->
                        <div x-show="activeTab === 'login'" x-transition:enter="transition ease-out duration-300 delay-200" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                            <span class="inline-flex rounded-full border border-emerald-300/20 bg-emerald-300/10 px-3 py-1 text-[11px] font-semibold text-emerald-200">ยินดีต้อนรับกลับ</span>
                            <h1 class="mt-3 text-2xl font-bold leading-tight xl:text-3xl">หนังสือทุกเล่ม<br>เปิดประตูสู่โลกใบใหม่</h1>
                            <p class="mt-2 text-xs leading-relaxed text-emerald-50/70">ค้นหา ยืม และติดตามหนังสือที่คุณสนใจได้ง่าย ๆ ในพื้นที่เดียว</p>
                            <div class="mt-6">
                                <p class="text-xs text-emerald-200 mb-2">ยังไม่มีบัญชีสมาชิก?</p>
                                <button type="button" @click="activeTab = 'register'; window.history.pushState({}, '', '{{ route('register') }}')" class="rounded-xl border border-emerald-300/40 bg-white/10 px-5 py-2 text-xs font-semibold text-white backdrop-blur transition hover:bg-white/20">
                                    สมัครสมาชิกฟรี →
                                </button>
                            </div>
                        </div>

                        <!-- Register Banner Content -->
                        <div x-show="activeTab === 'register'" x-transition:enter="transition ease-out duration-300 delay-200" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" style="display: none;">
                            <span class="inline-flex rounded-full border border-emerald-300/20 bg-emerald-300/10 px-3 py-1 text-[11px] font-semibold text-emerald-200">เข้าร่วมชุมชนอ่านหนังสือ</span>
                            <h1 class="mt-3 text-2xl font-bold leading-tight xl:text-3xl">เริ่มต้นการอ่าน<br>อย่างไม่มีขีดจำกัด</h1>
                            <p class="mt-2 text-xs leading-relaxed text-emerald-50/70">สร้างบัญชีเพียง 1 นาทีเพื่อเข้าถึงคลังหนังสือและระบบสะสมแต้ม</p>
                            <div class="mt-6">
                                <p class="text-xs text-emerald-200 mb-2">มีบัญชีสมาชิกอยู่แล้ว?</p>
                                <button type="button" @click="activeTab = 'login'; window.history.pushState({}, '', '{{ route('login') }}')" class="rounded-xl border border-emerald-300/40 bg-white/10 px-5 py-2 text-xs font-semibold text-white backdrop-blur transition hover:bg-white/20">
                                    ← เข้าสู่ระบบ
                                </button>
                            </div>
                        </div>
                    </div>

                    <p class="relative text-[11px] text-white/40">© {{ now()->year }} {{ config('app.name', 'ระบบห้องสมุด') }}</p>
                </div>

                <!-- Form Area Content Grid (2 Columns behind sliding panel) -->
                <div class="grid w-full grid-cols-1 lg:grid-cols-2">
                    <!-- Left Slot (Register Form Container on desktop when panel moves right) -->
                    <div class="flex flex-col justify-center p-6 sm:p-8" :class="activeTab === 'register' ? 'block' : 'hidden lg:flex lg:opacity-0 lg:pointer-events-none'">
                        @if ($mode === 'register')
                            {{ $slot }}
                        @else
                            @include('auth.register-form-partial')
                        @endif
                    </div>

                    <!-- Right Slot (Login Form Container on desktop when panel is left) -->
                    <div class="flex flex-col justify-center p-6 sm:p-8" :class="activeTab === 'login' ? 'block' : 'hidden lg:flex lg:opacity-0 lg:pointer-events-none'">
                        @if ($mode === 'login')
                            {{ $slot }}
                        @else
                            @include('auth.login-form-partial')
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
