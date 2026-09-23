<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>คลังหนังสือ — {{ config('app.name') }}</title>
    <meta name="description" content="ค้นหาและสำรวจหนังสือในห้องสมุด {{ config('app.name') }} — เปิดให้ทุกคนเข้าถึง">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=noto-sans-thai:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased">

    {{-- Navbar --}}
    <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/90 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-500 text-xl shadow-sm">📚</div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-emerald-600">ห้องสมุด</p>
                    <p class="text-sm font-bold text-slate-900 leading-none">{{ config('app.name') }}</p>
                </div>
            </a>
            <nav class="flex items-center gap-2">
                @auth
                    <a href="{{ url('/dashboard') }}" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">แดชบอร์ด</a>
                @else
                    <a href="{{ route('login') }}" class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:border-emerald-400">เข้าสู่ระบบ</a>
                    <a href="{{ route('register') }}" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">สมัครสมาชิก</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 space-y-6">

        {{-- Hero Search --}}
        <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-700 to-teal-700 p-6 text-white shadow-lg sm:p-10">
            <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full border-[48px] border-white/5"></div>
            <div class="relative max-w-3xl">
                <h1 class="text-3xl font-extrabold sm:text-4xl">คลังหนังสือสาธารณะ</h1>
                <p class="mt-2 text-emerald-100">ค้นหาและสำรวจหนังสือ {{ $books->total() }} รายการ — ไม่จำเป็นต้อง Login</p>
                <form method="GET" action="{{ route('catalog.index') }}" class="mt-6 grid gap-3 rounded-2xl bg-white p-3 shadow-xl sm:grid-cols-[minmax(0,1fr)_14rem_auto]">
                    <input name="search" type="search" value="{{ $search }}" placeholder="ชื่อหนังสือหรือผู้แต่ง..." autocomplete="off" class="form-control border-0 bg-slate-50 shadow-none text-slate-800">
                    <select name="category" class="form-control border-0 bg-slate-50 shadow-none text-slate-800">
                        <option value="">ทุกหมวดหมู่</option>
                        @foreach($categories as $cat)<option value="{{ $cat->id }}" @selected($categoryId === $cat->id)>{{ $cat->name }}</option>@endforeach
                    </select>
                    <button type="submit" class="button-primary px-6">ค้นหา</button>
                </form>

                {{-- Advanced search --}}
                <details class="mt-3 text-sm" @if($publisher || $year) open @endif>
                    <summary class="cursor-pointer text-emerald-200 hover:text-white">▸ ค้นหาขั้นสูง (สำนักพิมพ์ / ปีพิมพ์)</summary>
                    <form method="GET" action="{{ route('catalog.index') }}" class="mt-3 grid gap-3 rounded-2xl bg-white/10 p-4 backdrop-blur sm:grid-cols-3">
                        <input type="hidden" name="search" value="{{ $search }}">
                        <input type="hidden" name="category" value="{{ $categoryId ?: '' }}">
                        <div><label class="block text-xs font-semibold text-emerald-100 mb-1">สำนักพิมพ์</label><input type="text" name="publisher" value="{{ $publisher }}" class="form-control border-0 bg-white/90 text-slate-800 shadow-none text-sm"></div>
                        <div><label class="block text-xs font-semibold text-emerald-100 mb-1">ปีพิมพ์</label><input type="number" name="year" value="{{ $year ?: '' }}" placeholder="เช่น 2566" min="1000" max="{{ date('Y') + 1 }}" class="form-control border-0 bg-white/90 text-slate-800 shadow-none text-sm"></div>
                        <div class="flex items-end gap-2"><button type="submit" class="button-primary text-sm px-5">ค้นหา</button>@if($publisher || $year)<a href="{{ route('catalog.index') }}" class="button-secondary text-sm px-5">ล้าง</a>@endif</div>
                    </form>
                </details>
            </div>
        </section>

        {{-- Guest CTA --}}
        @guest
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-6 py-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <p class="font-semibold text-emerald-800">อยากยืมหนังสือ?</p>
                <p class="text-sm text-emerald-700">สมัครสมาชิกฟรีเพื่อส่งคำขอยืมและติดตามสถานะได้ทันที</p>
            </div>
            <div class="flex gap-2 shrink-0">
                <a href="{{ route('login') }}" class="button-secondary text-sm">เข้าสู่ระบบ</a>
                <a href="{{ route('register') }}" class="button-primary text-sm">สมัครสมาชิก</a>
            </div>
        </div>
        @endguest

        {{-- Results --}}
        <div class="flex items-center justify-between">
            <p class="text-sm text-slate-500">พบ <span class="font-semibold text-slate-800">{{ $books->total() }}</span> รายการ</p>
        </div>

        <section class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
            @forelse($books as $book)
                <article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-900/5">
                    <div class="relative aspect-[4/5] overflow-hidden bg-gradient-to-br from-slate-100 to-slate-200">
                        @if($book->coverImageUrl())<img src="{{ $book->coverImageUrl() }}" alt="{{ $book->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">@else<div class="flex h-full flex-col items-center justify-center gap-2 text-slate-400"><svg class="h-10 w-10 stroke-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></div>@endif
                        <span class="status-badge absolute right-3 top-3 inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold shadow-md backdrop-blur-md border transition-all {{ $book->stock > 0 ? 'bg-white/95 text-emerald-800 border-emerald-200/80' : 'bg-rose-600/95 text-white border-rose-500/80' }}">{{ $book->stock > 0 ? 'พร้อมยืม' : 'ถูกยืมหมด' }}</span>
                    </div>
                    <div class="flex flex-1 flex-col p-4">
                        <span class="text-xs font-semibold text-emerald-600">{{ $book->category->name ?? 'ไม่ระบุหมวดหมู่' }}</span>
                        <p class="mt-1 line-clamp-2 font-bold leading-snug text-slate-900">{{ $book->title }}</p>
                        <p class="mt-1 truncate text-sm text-slate-500">{{ $book->author }}</p>
                        @if($book->publisher || $book->year)
                            <p class="mt-0.5 truncate text-xs text-slate-400">{{ collect([$book->publisher, $book->year])->filter()->implode(' · ') }}</p>
                        @endif
                        <div class="mt-auto pt-4">
                            @auth
                                <a href="{{ route('books.show', $book) }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-800">ดูรายละเอียด →</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-500 hover:text-emerald-600">Login เพื่อยืม →</a>
                            @endauth
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white/70 px-6 py-16 text-center"><div class="text-5xl">🔎</div><h3 class="mt-4 text-lg font-bold text-slate-900">ไม่พบหนังสือที่ค้นหา</h3><a href="{{ route('catalog.index') }}" class="button-secondary mt-5">ล้างตัวกรอง</a></div>
            @endforelse
        </section>

        <div>{{ $books->links() }}</div>
    </main>
</body>
</html>
