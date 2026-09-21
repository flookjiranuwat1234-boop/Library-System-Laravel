<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">{{ $book->title }}</h2>
    </x-slot>

    <div class="page-shell">
        <div class="page-container">
            <div class="panel p-5 sm:p-8">

                @if(session('success'))
                    <div class="alert-success mb-4">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert-error mb-4">{{ session('error') }}</div>
                @endif

                <div class="flex flex-col gap-8 md:flex-row">
                    {{-- Cover --}}
                    <div class="md:w-1/3">
                        @if($book->coverImageUrl())
                            <img src="{{ $book->coverImageUrl() }}" alt="{{ $book->title }}" class="w-full rounded-2xl shadow-lg">
                        @else
                            <div class="flex h-64 w-full items-center justify-center rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 text-slate-400 shadow-lg">
                                <span class="text-6xl">📘</span>
                            </div>
                        @endif

                        {{-- QR Code (admin only) --}}
                        @if(Auth::user()->role === 'admin')
                            <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-center">
                                <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">QR Code</p>
                                <div id="book-qr-inline" class="flex justify-center"></div>
                                <a href="{{ route('books.qr', $book) }}" target="_blank" class="mt-2 inline-block text-xs font-semibold text-violet-600 hover:text-violet-800">🖨️ เปิดหน้าพิมพ์</a>
                            </div>
                        @endif
                    </div>

                    {{-- Details --}}
                    <div class="md:w-2/3">
                        <h1 class="text-3xl font-bold text-slate-900">{{ $book->title }}</h1>
                        <p class="mt-1 text-xl text-slate-600">ผู้แต่ง: {{ $book->author }}</p>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="inline-block rounded-full bg-emerald-100 px-3 py-1 text-sm font-semibold text-emerald-700">{{ $book->category->name ?? 'ไม่ระบุหมวดหมู่' }}</span>
                            <span class="inline-block rounded-full px-3 py-1 text-sm font-semibold {{ $book->stock > 0 ? 'bg-sky-100 text-sky-700' : 'bg-rose-100 text-rose-700' }}">คงเหลือ: {{ $book->stock }} เล่ม</span>
                        </div>

                        {{-- Extra info --}}
                        @if($book->isbn || $book->publisher || $book->year || $book->pages)
                            <dl class="mt-4 grid grid-cols-2 gap-3 rounded-2xl bg-slate-50 p-4 sm:grid-cols-4">
                                @if($book->isbn)
                                    <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">ISBN</dt><dd class="mt-0.5 text-sm font-medium text-slate-700">{{ $book->isbn }}</dd></div>
                                @endif
                                @if($book->publisher)
                                    <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">สำนักพิมพ์</dt><dd class="mt-0.5 text-sm font-medium text-slate-700">{{ $book->publisher }}</dd></div>
                                @endif
                                @if($book->year)
                                    <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">ปีพิมพ์</dt><dd class="mt-0.5 text-sm font-medium text-slate-700">{{ $book->year }}</dd></div>
                                @endif
                                @if($book->pages)
                                    <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">จำนวนหน้า</dt><dd class="mt-0.5 text-sm font-medium text-slate-700">{{ number_format($book->pages) }} หน้า</dd></div>
                                @endif
                            </dl>
                        @endif

                        <div class="prose max-w-none mt-6">
                            <h3 class="text-lg font-semibold">รายละเอียด</h3>
                            <p class="text-slate-600">{{ $book->description ?: 'ยังไม่มีรายละเอียดหนังสือ' }}</p>
                        </div>

                        {{-- Borrow Action --}}
                        <div class="mt-8 border-t pt-6">
                            @if($currentBorrow)
                                <div class="inline-flex items-center rounded-xl bg-amber-100 px-5 py-3 font-semibold text-amber-800">
                                    {{ $currentBorrow->status === 'pending' ? 'คำขอยืมกำลังรอผู้ดูแลอนุมัติ' : 'คุณกำลังยืมหนังสือเล่มนี้อยู่' }}
                                </div>
                                <p class="mt-2 text-sm text-slate-500">{{ $currentBorrow->status === 'pending' ? 'ระบบจะกำหนดวันยืมและวันคืนหลังได้รับอนุมัติ' : 'ตรวจสอบวันครบกำหนดได้ที่รายการยืมของฉัน' }}</p>
                            @elseif($book->stock > 0)
                                <form action="{{ route('borrows.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                                    <button type="submit" class="button-primary px-6 py-3">ส่งคำขอยืมหนังสือ</button>
                                </form>
                            @else
                                <button disabled class="cursor-not-allowed rounded-xl bg-slate-300 px-6 py-3 font-bold text-slate-500">หนังสือถูกยืมหมดแล้ว</button>
                            @endif
                        </div>

                        @if(Auth::user()->role === 'admin')
                            <div class="mt-4 flex gap-3">
                                <a href="{{ route('books.edit', $book) }}" class="button-secondary text-sm">✏️ แก้ไขข้อมูล</a>
                                <a href="{{ route('books.qr', $book) }}" target="_blank" class="button-secondary text-sm">🔲 พิมพ์ QR Code</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->role === 'admin')
        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
        <script>
            QRCode.toCanvas(document.createElement('canvas'), '{{ $bookUrl }}', { width: 140 }, function (err, canvas) {
                if (!err) document.getElementById('book-qr-inline').appendChild(canvas);
            });
        </script>
        @endpush
    @endif
</x-app-layout>
