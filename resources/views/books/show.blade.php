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
                            <div class="relative overflow-hidden rounded-2xl shadow-lg bg-gradient-to-br from-amber-800 via-amber-900 to-slate-900 aspect-[3/4]">
                                <img src="{{ $book->coverImageUrl() }}" alt="{{ $book->title }}" class="h-full w-full object-cover rounded-2xl" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                <div class="hidden flex h-full flex-col items-center justify-center p-6 text-center text-amber-100 relative">
                                    <div class="absolute inset-3 border border-amber-400/30 rounded-xl pointer-events-none"></div>
                                    <span class="text-5xl mb-3 filter drop-shadow">📚</span>
                                    <h4 class="text-base font-bold leading-snug line-clamp-3 text-amber-50 drop-shadow">{{ $book->title }}</h4>
                                    <p class="text-xs mt-1 text-amber-200/80 truncate w-full">{{ $book->author }}</p>
                                </div>
                            </div>
                        @else
                            <div class="flex h-64 w-full items-center justify-center rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 text-slate-400 shadow-lg">
                                <span class="text-6xl">📘</span>
                            </div>
                        @endif

                        {{-- QR Code (admin only) --}}
                        @if(Auth::user()->role === 'admin')
                            <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-center">
                                <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">QR Code</p>
                                <div id="book-qr-inline" class="flex justify-center">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ urlencode($bookUrl) }}&color=065f46" alt="QR Code" width="140" height="140" class="rounded-xl border border-slate-200 bg-white p-1">
                                </div>
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

                        {{-- Role-based Actions --}}
                        <div class="mt-8 border-t border-slate-100 pt-6">
                            @if(Auth::user()->role === 'admin')
                                <div class="rounded-2xl border border-emerald-200 bg-emerald-50/50 p-5 space-y-4">
                                    <div class="flex items-center justify-between border-b border-emerald-200/60 pb-3">
                                        <div>
                                            <h4 class="text-sm font-bold text-slate-900">เคาน์เตอร์ทำรายการยืม–คืน (Admin Circulation Counter)</h4>
                                            <p class="text-xs text-slate-500">เมื่อสแกน QR Code หนังสือเล่มนี้ สามารถรับคืนหรือทำรายการยืมให้สมาชิกได้ทันที</p>
                                        </div>
                                    </div>

                                    {{-- Active Borrows for this Book --}}
                                    @if($activeBorrows->count() > 0)
                                        <div class="space-y-2">
                                            <p class="text-xs font-bold text-slate-700">รายการสมาชิกที่กำลังยืม/รออนุมัติหนังสือเล่มนี้ ({{ $activeBorrows->count() }} รายการ):</p>
                                            <div class="divide-y divide-slate-200/60 rounded-xl border border-slate-200 bg-white p-2">
                                                @foreach($activeBorrows as $record)
                                                    <div class="flex items-center justify-between p-2.5 text-xs gap-2">
                                                        <div class="flex items-center gap-2 min-w-0">
                                                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-800">
                                                                {{ mb_substr($record->user->name, 0, 1) }}
                                                            </span>
                                                            <div class="min-w-0">
                                                                <p class="font-bold text-slate-900 truncate">{{ $record->user->name }}</p>
                                                                <p class="text-[10px] text-slate-400">
                                                                    {{ $record->borrowed_at ? 'ยืมเมื่อ '.$record->borrowed_at->locale('th')->translatedFormat('j M Y') : 'รออนุมัติ' }}
                                                                    @if($record->due_date) • กำหนดคืน {{ $record->due_date->locale('th')->translatedFormat('j M Y') }} @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center gap-1.5 shrink-0">
                                                            @if($record->status === 'pending')
                                                                <form action="{{ route('borrows.approve', $record) }}" method="POST" class="inline">
                                                                    @csrf
                                                                    <button type="submit" class="rounded-lg bg-emerald-600 px-2.5 py-1 text-xs font-bold text-white hover:bg-emerald-700">
                                                                        อนุมัติยืม
                                                                    </button>
                                                                </form>
                                                            @elseif(in_array($record->status, ['borrowed', 'overdue']))
                                                                @if($record->canRenew())
                                                                    <form action="{{ route('borrows.renew', $record) }}" method="POST" class="inline">
                                                                        @csrf
                                                                        <button type="submit" class="rounded-lg bg-amber-500 px-2.5 py-1 text-xs font-bold text-white hover:bg-amber-600">
                                                                            ต่ออายุ (+7วัน)
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                                <form action="{{ route('borrows.return', $record) }}" method="POST" class="inline">
                                                                    @csrf
                                                                    <button type="submit" class="rounded-lg bg-emerald-600 px-2.5 py-1 text-xs font-bold text-white hover:bg-emerald-700">
                                                                        รับคืนหนังสือ
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    {{-- Quick Register & Issue for Walk-in Users --}}
                                    @if($book->stock > 0)
                                        <div x-data="{ showQuickRegisterModal: false }" class="rounded-xl border border-emerald-200 bg-white p-3.5 space-y-2">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-xs font-bold text-slate-800">ทำรายการยืมสำหรับบุคคลทั่วไป</p>
                                                    <p class="text-[11px] text-slate-500">สำหรับผู้ใช้บริการที่ยังไม่ได้เป็นสมาชิกในระบบ</p>
                                                </div>
                                                <button type="button" @click="showQuickRegisterModal = true" class="inline-flex items-center gap-1 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 px-3.5 py-2 rounded-xl shadow-sm transition shrink-0">
                                                    ยืมสำหรับบุคคลทั่วไป
                                                </button>
                                            </div>

                                            {{-- Quick Register & Issue Modal --}}
                                            <div x-show="showQuickRegisterModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
                                                <div @click.away="showQuickRegisterModal = false" class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl space-y-4">
                                                    <div class="flex items-center justify-between border-b pb-3">
                                                        <h3 class="text-base font-bold text-slate-900">ยืมสำหรับบุคคลทั่วไป</h3>
                                                        <button @click="showQuickRegisterModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg">&times;</button>
                                                    </div>
                                                    <form action="{{ route('borrows.store') }}" method="POST" class="space-y-4">
                                                        @csrf
                                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                                        <div>
                                                            <label class="block text-xs font-bold text-slate-700 mb-1">ชื่อ-นามสกุล ผู้ขอยืม *</label>
                                                            <input type="text" name="quick_name" required placeholder="เช่น นายสมชาย ใจดี" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs focus:border-emerald-500 focus:outline-none">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-bold text-slate-700 mb-1">อีเมล / เบอร์โทรติดต่อ (ถ้ามี)</label>
                                                            <input type="email" name="quick_email" placeholder="somchai@example.com (เว้นว่างได้)" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs focus:border-emerald-500 focus:outline-none">
                                                        </div>
                                                        <div class="rounded-xl bg-amber-50 border border-amber-200/80 p-3 text-[11px] text-amber-800 space-y-1">
                                                            <p class="font-bold flex items-center gap-1">💡 บันทึกข้อมูลเข้าสู่ระบบสมาชิกอัตโนมัติ:</p>
                                                            <p>• ระบบจะสร้างบัญชีสมาชิกให้อัตโนมัติ เพื่อให้ผู้ยืมสามารถติดตามประวัติและทำรายการยืม-คืนได้</p>
                                                            <p>• รหัสผ่านเริ่มต้นสำหรับเข้าสู่ระบบคือ: <code class="font-bold text-amber-900 bg-amber-100 px-1.5 py-0.5 rounded">password123</code></p>
                                                        </div>
                                                        <div class="flex items-center justify-end gap-2 pt-2 border-t">
                                                            <button type="button" @click="showQuickRegisterModal = false" class="rounded-xl border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">ยกเลิก</button>
                                                            <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white hover:bg-emerald-700 shadow-md">บันทึกการยืม</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="pt-2 flex flex-wrap gap-2 border-t border-emerald-200/60">
                                        <a href="{{ route('books.edit', $book) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-300 bg-white px-4 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                                            แก้ไขข้อมูลหนังสือ
                                        </a>
                                        <a href="{{ route('books.qr', $book) }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-300 bg-white px-4 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                                            พิมพ์ QR Code
                                        </a>
                                    </div>
                                </div>
                            @else
                                @if($currentBorrow)
                                    <div class="inline-flex items-center rounded-xl bg-amber-100 px-5 py-3 font-semibold text-amber-800">
                                        {{ $currentBorrow->status === 'pending' ? 'คำขอยืมกำลังรอผู้ดูแลอนุมัติ' : 'คุณกำลังยืมหนังสือเล่มนี้อยู่' }}
                                    </div>
                                    <p class="mt-2 text-sm text-slate-500">{{ $currentBorrow->status === 'pending' ? 'ระบบจะกำหนดวันยืมและวันคืนหลังได้รับอนุมัติ' : 'ตรวจสอบวันครบกำหนดได้ที่รายการยืมของฉัน' }}</p>
                                @elseif($book->stock > 0)
                                    <form action="{{ route('borrows.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                        <button type="submit" class="button-primary px-6 py-3 font-bold shadow-md">ส่งคำขอยืมหนังสือ</button>
                                    </form>
                                @else
                                    <button disabled class="cursor-not-allowed rounded-xl bg-slate-200 px-6 py-3 font-bold text-slate-500">หนังสือถูกยืมหมดแล้ว</button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
