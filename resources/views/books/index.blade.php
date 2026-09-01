<x-app-layout>
    <x-slot name="header"><div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between"><div><p class="text-sm font-semibold text-emerald-600">คลังความรู้</p><h2 class="page-title">หนังสือทั้งหมด</h2></div><p id="catalog-total" class="text-sm text-slate-500">พบ {{ $books->total() }} รายการ</p></div></x-slot>

    <div class="page-shell"><div x-data="catalogSearch" class="page-container space-y-6">
        @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert-error">{{ session('error') }}</div>@endif

        <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-700 to-teal-700 p-6 text-white shadow-lg sm:p-8">
            <div class="absolute -right-12 -top-16 h-52 w-52 rounded-full border-[36px] border-white/5"></div>
            <div class="relative max-w-3xl"><h1 class="text-2xl font-bold sm:text-3xl">วันนี้อยากอ่านอะไร?</h1><p class="mt-2 text-sm text-emerald-100">ค้นหาจากชื่อหนังสือ ผู้แต่ง หรือเลือกสำรวจตามหมวดหมู่</p>
                <form x-ref="form" @submit.prevent="update" method="GET" action="{{ route('books.index') }}" class="mt-6 grid gap-3 rounded-2xl bg-white p-3 shadow-xl sm:grid-cols-[minmax(0,1fr)_14rem_auto]">
                    <label class="sr-only" for="search">ค้นหาในคลังหนังสือ</label><input @input.debounce.300ms="update" id="search" name="search" type="search" value="{{ $search }}" placeholder="ชื่อหนังสือหรือผู้แต่ง..." autocomplete="off" class="form-control border-0 bg-slate-50 shadow-none">
                    <label class="sr-only" for="category">หมวดหมู่</label><select @change="update" id="category" name="category" class="form-control border-0 bg-slate-50 shadow-none"><option value="">ทุกหมวดหมู่</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected($categoryId === $category->id)>{{ $category->name }}</option>@endforeach</select>
                    <button type="submit" class="button-primary gap-2 px-6"><svg x-show="loading" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg><span x-text="loading ? 'กำลังค้นหา' : 'ค้นหา'">ค้นหา</span></button>
                </form>
            </div>
        </section>

        <div class="flex flex-wrap items-center justify-between gap-3"><div><h2 class="text-xl font-bold text-slate-900">รายการหนังสือ</h2><p id="catalog-count" aria-live="polite" class="text-sm text-slate-500">พบ {{ $books->total() }} รายการ</p></div>@if(Auth::user()->role === 'admin')<a href="{{ route('books.create') }}" class="button-primary">+ เพิ่มหนังสือ</a>@endif</div>

        <section id="catalog-results" :class="loading && 'opacity-50'" class="grid grid-cols-2 gap-4 transition-opacity sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
            @forelse($books as $book)
                <article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-900/5">
                    <a href="{{ route('books.show', $book) }}" class="relative block aspect-[4/5] overflow-hidden bg-gradient-to-br from-slate-100 to-slate-200">
                        @if($book->coverImageUrl())<img src="{{ $book->coverImageUrl() }}" alt="{{ $book->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">@else<div class="flex h-full flex-col items-center justify-center gap-2 text-slate-400"><span class="text-5xl">📘</span><span class="text-xs">ไม่มีรูปปก</span></div>@endif
                        <span class="status-badge absolute right-3 top-3 shadow-sm {{ $book->stock > 0 ? 'bg-white/95 text-emerald-700' : 'bg-rose-600 text-white' }}">{{ $book->stock > 0 ? 'พร้อมยืม '.$book->stock : 'ถูกยืมหมด' }}</span>
                    </a>
                    <div class="flex flex-1 flex-col p-4"><span class="text-xs font-semibold text-emerald-600">{{ $book->category->name ?? 'ไม่ระบุหมวดหมู่' }}</span><a href="{{ route('books.show', $book) }}" class="mt-1 line-clamp-2 font-bold leading-snug text-slate-900 group-hover:text-emerald-700">{{ $book->title }}</a><p class="mt-1 truncate text-sm text-slate-500">{{ $book->author }}</p>
                        <div class="mt-auto flex items-center justify-between gap-2 pt-4"><a href="{{ route('books.show', $book) }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-800">ดูรายละเอียด →</a>@if(Auth::user()->role === 'admin')<div class="flex gap-2"><a href="{{ route('books.edit', $book) }}" class="text-xs font-semibold text-sky-600">แก้ไข</a><form action="{{ route('books.destroy', $book) }}" method="POST" onsubmit="return confirm('ยืนยันการลบหนังสือเล่มนี้หรือไม่?');">@csrf @method('DELETE')<button class="text-xs font-semibold text-rose-600">ลบ</button></form></div>@endif</div>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white/70 px-6 py-16 text-center"><div class="text-5xl">🔎</div><h3 class="mt-4 text-lg font-bold text-slate-900">ไม่พบหนังสือที่ค้นหา</h3><p class="mt-1 text-sm text-slate-500">ลองเปลี่ยนคำค้นหาหรือเลือกทุกหมวดหมู่</p><a href="{{ route('books.index') }}" class="button-secondary mt-5">ล้างตัวกรอง</a></div>
            @endforelse
        </section>
        <div id="catalog-pagination">{{ $books->links() }}</div>
    </div></div>
</x-app-layout>
