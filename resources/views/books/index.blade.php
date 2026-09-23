<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">คลังความรู้</p>
                <h2 class="page-title text-2xl font-black text-slate-900">หนังสือ & หมวดหมู่</h2>
            </div>
            <div class="flex items-center gap-2">
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('books.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-emerald-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>เพิ่มหนังสือ</span>
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="page-shell" x-data="{ manageCategoriesModal: false, editingCatId: null, editingCatName: '' }">
        <div class="page-container space-y-6">
            @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="alert-error">{{ session('error') }}</div>@endif

            {{-- Single Unified Search & Category Filter Section --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
                {{-- Search Bar --}}
                <form method="GET" action="{{ route('books.index') }}" class="flex flex-col sm:flex-row gap-3">
                    @if($categoryId)
                        <input type="hidden" name="category" value="{{ $categoryId }}">
                    @endif
                    <div class="relative flex-1">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input id="search" name="search" type="search" value="{{ $search }}" placeholder="ค้นหาชื่อหนังสือ ผู้แต่ง หรือ ISBN..." autocomplete="off" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 pl-10 pr-4 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <span>ค้นหา</span>
                        </button>
                        @if($search || $categoryId || $publisher || $year || $isbn)
                            <a href="{{ route('books.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-900" title="ล้างตัวกรองทั้งหมด">
                                ล้างตัวกรอง
                            </a>
                        @endif
                    </div>
                </form>

                {{-- Interactive Category Filter Chips --}}
                <div class="border-t border-slate-100 pt-4">
                    <div class="flex items-center justify-between gap-2 mb-2.5">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">เลือกตามหมวดหมู่</span>
                        @if(Auth::user()->role === 'admin')
                            <button @click="manageCategoriesModal = true" type="button" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-emerald-700 transition">
                                <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>จัดการหมวดหมู่</span>
                            </button>
                        @endif
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('books.index', array_filter(['search' => $search, 'publisher' => $publisher, 'year' => $year, 'isbn' => $isbn])) }}"
                           class="inline-flex items-center gap-1.5 rounded-xl px-3.5 py-1.5 text-xs font-bold transition border {{ empty($categoryId) ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                            <span>ทั้งหมด</span>
                            <span class="rounded-full px-1.5 py-0.5 text-[10px] font-semibold {{ empty($categoryId) ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-600' }}">{{ $books->total() }}</span>
                        </a>

                        @foreach($categories as $cat)
                            <a href="{{ route('books.index', array_filter(['category' => $cat->id, 'search' => $search, 'publisher' => $publisher, 'year' => $year, 'isbn' => $isbn])) }}"
                               class="inline-flex items-center gap-1.5 rounded-xl px-3.5 py-1.5 text-xs font-bold transition border {{ $categoryId === $cat->id ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100 hover:border-emerald-300' }}">
                                <span>{{ $cat->name }}</span>
                                <span class="rounded-full px-1.5 py-0.5 text-[10px] font-semibold {{ $categoryId === $cat->id ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-600' }}">{{ $cat->books_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Advanced Search Accordion --}}
                <details class="border-t border-slate-100 pt-3 text-xs" @if($publisher || $year || $isbn) open @endif>
                    <summary class="cursor-pointer font-semibold text-slate-500 hover:text-emerald-700 transition select-none inline-flex items-center gap-1.5">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        <span>ค้นหาขั้นสูง (ISBN / สำนักพิมพ์ / ปีพิมพ์)</span>
                    </summary>
                    <form method="GET" action="{{ route('books.index') }}" class="mt-3 grid gap-3 rounded-2xl bg-slate-50/80 p-4 sm:grid-cols-3 border border-slate-200/60">
                        <input type="hidden" name="search" value="{{ $search }}">
                        <input type="hidden" name="category" value="{{ $categoryId ?: '' }}">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">ISBN</label>
                            <input type="text" name="isbn" value="{{ $isbn }}" placeholder="เช่น 9789740216..." class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">สำนักพิมพ์</label>
                            <input type="text" name="publisher" value="{{ $publisher }}" placeholder="ชื่อสำนักพิมพ์..." class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">ปีพิมพ์</label>
                            <input type="number" name="year" value="{{ $year ?: '' }}" placeholder="เช่น 2566" min="1000" max="{{ date('Y') + 1 }}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                        </div>
                        <div class="sm:col-span-3 flex items-center gap-2 pt-1">
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white transition hover:bg-emerald-700">ค้นหาขั้นสูง</button>
                            @if($publisher || $year || $isbn)
                                <a href="{{ route('books.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">ล้างตัวกรอง</a>
                            @endif
                        </div>
                    </form>
                </details>
            </div>

            {{-- Clean Admin Category Management Modal --}}
            @if(Auth::user()->role === 'admin')
                <template x-teleport="body">
                    <div x-show="manageCategoriesModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/60 backdrop-blur-sm p-4" x-transition>
                        <div @click.away="manageCategoriesModal = false" class="w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl space-y-5">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <div class="flex items-center gap-2">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </span>
                                    <h3 class="text-base font-bold text-slate-900">จัดการหมวดหมู่หนังสือ</h3>
                                </div>
                                <button @click="manageCategoriesModal = false" type="button" class="rounded-xl p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            {{-- Quick Add Category Form inside Modal --}}
                            <form action="{{ route('categories.store') }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <input type="text" name="name" required placeholder="พิมพ์ชื่อหมวดหมู่ใหม่..." class="flex-1 rounded-xl border border-slate-300 bg-slate-50 px-3.5 py-2 text-xs text-slate-800 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                                <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white hover:bg-emerald-700 transition shrink-0">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <span>เพิ่มหมวดหมู่</span>
                                </button>
                            </form>

                            {{-- List of Categories --}}
                            <div class="max-h-80 overflow-y-auto space-y-2 pr-1">
                                @foreach($categories as $cat)
                                    <div class="flex items-center justify-between gap-3 rounded-2xl border border-slate-200/80 bg-slate-50/50 p-3 hover:bg-slate-100/50 transition" x-data="{ editing: false, name: '{{ addslashes($cat->name) }}' }">
                                        <div class="flex-1">
                                            <template x-if="!editing">
                                                <div class="flex items-center gap-2">
                                                    <span class="font-bold text-xs text-slate-800" x-text="name"></span>
                                                    <span class="rounded-full bg-slate-200 px-2 py-0.5 text-[10px] font-semibold text-slate-600">{{ $cat->books_count }} เล่ม</span>
                                                </div>
                                            </template>
                                            <template x-if="editing">
                                                <form action="{{ route('categories.update', $cat) }}" method="POST" class="flex items-center gap-1.5">
                                                    @csrf @method('PUT')
                                                    <input type="text" name="name" x-model="name" required class="w-full rounded-lg border border-slate-300 bg-white px-2.5 py-1 text-xs text-slate-800 focus:border-emerald-500 focus:outline-none">
                                                    <button type="submit" class="rounded-lg bg-emerald-600 px-2.5 py-1 text-[11px] font-bold text-white hover:bg-emerald-700">บันทึก</button>
                                                    <button type="button" @click="editing = false" class="rounded-lg border border-slate-300 bg-white px-2 py-1 text-[11px] font-semibold text-slate-600 hover:bg-slate-50">ยกเลิก</button>
                                                </form>
                                            </template>
                                        </div>

                                        <div class="flex items-center gap-1 shrink-0" x-show="!editing">
                                            <button @click="editing = true" type="button" title="แก้ไข" class="rounded-lg border border-slate-200 bg-white px-2 py-1 text-[11px] font-semibold text-sky-700 hover:bg-sky-50 transition">
                                                แก้ไข
                                            </button>
                                            <form action="{{ route('categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('{{ $cat->books_count > 0 ? 'ยืนยันการลบหมวดหมู่ '.$cat->name.' หรือไม่? (หนังสือ '.$cat->books_count.' เล่มในหมวดนี้จะกลายเป็นไม่ระบุหมวดหมู่)' : 'ยืนยันการลบหมวดหมู่ '.$cat->name.' หรือไม่?' }}');" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" title="ลบ" class="rounded-lg border border-slate-200 bg-white px-2 py-1 text-[11px] font-semibold text-rose-700 hover:bg-rose-50 transition">
                                                    ลบ
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </template>
            @endif

            {{-- Books Results Grid --}}
            <div class="flex items-center justify-between pt-2">
                <span class="text-sm font-semibold text-slate-500">พบหนังสือ {{ $books->total() }} รายการ</span>
            </div>

            <section id="catalog-results" class="grid grid-cols-2 gap-4 transition-opacity sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                @forelse($books as $book)
                    <article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-900/5">
                        <a href="{{ route('books.show', $book) }}" class="relative block aspect-[4/5] overflow-hidden bg-gradient-to-br from-slate-100 to-slate-200">
                            @if($book->coverImageUrl())
                                <img src="{{ $book->coverImageUrl() }}" alt="{{ $book->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            @else
                                <div class="flex h-full flex-col items-center justify-center gap-2 text-slate-400">
                                    <svg class="h-12 w-12 stroke-[1.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    <span class="text-xs">ไม่มีรูปปก</span>
                                </div>
                            @endif
                            <span class="status-badge absolute right-3 top-3 inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold shadow-md backdrop-blur-md border transition-all {{ $book->stock > 0 ? 'bg-white/95 text-emerald-800 border-emerald-200/80' : 'bg-rose-600/95 text-white border-rose-500/80' }}">
                                {{ $book->stock > 0 ? 'พร้อมยืม '.$book->stock : 'ถูกยืมหมด' }}
                            </span>
                        </a>
                        <div class="flex flex-1 flex-col p-4">
                            <span class="text-xs font-semibold text-emerald-600">{{ $book->category->name ?? 'ไม่ระบุหมวดหมู่' }}</span>
                            <a href="{{ route('books.show', $book) }}" class="mt-1 line-clamp-2 font-bold leading-snug text-slate-900 group-hover:text-emerald-700">{{ $book->title }}</a>
                            <p class="mt-1 truncate text-sm text-slate-500">{{ $book->author }}</p>
                            @if($book->isbn)<p class="mt-0.5 truncate text-xs text-slate-400">ISBN: {{ $book->isbn }}</p>@endif
                            <div class="mt-auto flex flex-col gap-2.5 border-t border-slate-100 pt-3">
                                <div class="flex items-center justify-between">
                                    <a href="{{ route('books.show', $book) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-emerald-600 hover:text-emerald-800 transition">
                                        <span>ดูรายละเอียด</span>
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                </div>
                                @if(Auth::user()->role === 'admin')
                                    <div class="flex items-center gap-1.5 border-t border-slate-100 pt-2">
                                        <a href="{{ route('books.qr', $book) }}" target="_blank" title="QR Code" class="flex-1 inline-flex items-center justify-center rounded-lg bg-slate-100 px-1.5 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                                            QR Code
                                        </a>
                                        <a href="{{ route('books.edit', $book) }}" title="แก้ไข" class="flex-1 inline-flex items-center justify-center rounded-lg bg-sky-50 px-1.5 py-1 text-xs font-semibold text-sky-700 hover:bg-sky-100 transition">
                                            แก้ไข
                                        </a>
                                        <form action="{{ route('books.destroy', $book) }}" method="POST" onsubmit="return confirm('ยืนยันการลบหนังสือเล่มนี้หรือไม่?');" class="flex-1 inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" title="ลบ" class="w-full inline-flex items-center justify-center rounded-lg bg-rose-50 px-1.5 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition">
                                                ลบ
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white/70 px-6 py-16 text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-400 stroke-[1.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <h3 class="mt-4 text-lg font-bold text-slate-900">ไม่พบหนังสือที่ค้นหา</h3>
                        <p class="mt-1 text-sm text-slate-500">ลองเปลี่ยนคำค้นหาหรือเลือกทุกหมวดหมู่</p>
                        <a href="{{ route('books.index') }}" class="button-secondary mt-5">ล้างตัวกรอง</a>
                    </div>
                @endforelse
            </section>
            <div id="catalog-pagination">{{ $books->links() }}</div>
        </div>
    </div>
</x-app-layout>
