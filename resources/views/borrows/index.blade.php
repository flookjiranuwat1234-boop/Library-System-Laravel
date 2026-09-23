<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">
                    {{ Auth::user()->role === 'admin' ? 'Circulation Desk Management' : 'My Borrowing History' }}
                </p>
                <h2 class="page-title text-slate-900">
                    {{ Auth::user()->role === 'admin' ? 'รายการคำขอและประวัติยืม–คืน' : 'รายการยืมหนังสือของฉัน' }}
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">{{ Auth::user()->role === 'admin' ? 'อนุมัติคำขอยืม บันทึกการรับคืนหนังสือ และติดตามรายการเกินกำหนดส่ง' : 'ประวัติและสถานะรายการยืมหนังสือทั้งหมดของคุณ' }}</p>
            </div>
            <p class="text-sm font-semibold text-slate-500">พบทั้งหมด {{ number_format($borrows->total()) }} รายการ</p>
        </div>
    </x-slot>

    <div class="page-shell">
        <div class="page-container space-y-6">



            @if(session('success'))
                <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800 shadow-sm" role="alert">
                    <span class="text-lg">✅</span>
                    <div>{{ session('success') }}</div>
                </div>
            @endif
            @if(session('error'))
                <div class="flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm font-semibold text-rose-800 shadow-sm" role="alert">
                    <span class="text-lg">⚠️</span>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            {{-- Main Table Shell --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200/80 bg-slate-50/80 text-xs font-semibold text-slate-500">
                                <th class="px-4 py-3 text-left">รายการหนังสือ</th>
                                @if(Auth::user()->role === 'admin')
                                    <th class="px-4 py-3 text-left">ผู้ยืม (สมาชิก)</th>
                                @endif
                                <th class="px-4 py-3 text-left">วันที่ยืม</th>
                                <th class="px-4 py-3 text-left">กำหนดคืน</th>
                                <th class="px-4 py-3 text-center">สถานะ</th>
                                <th class="px-4 py-3 text-right">การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white text-xs">
                            @forelse($borrows as $borrow)
                                @php
                                    $isOverdue = in_array($borrow->status, ['borrowed', 'overdue']) && $borrow->due_date?->isPast();
                                @endphp
                                <tr class="transition duration-150 hover:bg-slate-50/70">
                                    {{-- Book Details --}}
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2.5">
                                            <div class="h-10 w-7 shrink-0 overflow-hidden rounded bg-slate-100 border border-slate-200">
                                                @if($borrow->book->coverImageUrl())
                                                    <img src="{{ $borrow->book->coverImageUrl() }}" alt="{{ $borrow->book->title }}" class="h-full w-full object-cover">
                                                @else
                                                    <div class="flex h-full w-full items-center justify-center text-slate-400 text-xs">📘</div>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <a href="{{ route('books.show', $borrow->book) }}" class="line-clamp-1 font-semibold text-slate-800 transition hover:text-emerald-600 text-xs">
                                                    {{ $borrow->book->title }}
                                                </a>
                                                <p class="truncate text-[11px] text-slate-400">{{ $borrow->book->author }}</p>
                                                <span class="inline-block text-[10px] font-medium text-emerald-600">
                                                    {{ $borrow->book->category->name ?? 'ทั่วไป' }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Member Details --}}
                                    @if(Auth::user()->role === 'admin')
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-[11px] font-semibold text-emerald-800">
                                                    {{ mb_substr($borrow->user->name, 0, 1) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-medium text-slate-800 truncate text-xs">{{ $borrow->user->name }}</p>
                                                    <p class="text-[11px] text-slate-400 truncate">{{ $borrow->user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                    @endif

                                    {{-- Borrow Date --}}
                                    <td class="whitespace-nowrap px-4 py-3 text-xs text-slate-600">
                                        @if($borrow->borrowed_at)
                                            <span class="font-normal text-slate-700">{{ $borrow->borrowed_at->locale('th')->translatedFormat('j M Y') }}</span>
                                            <p class="text-[11px] text-slate-400">{{ $borrow->borrowed_at->locale('th')->diffForHumans() }}</p>
                                        @else
                                            <span class="text-slate-400 italic">รออนุมัติ</span>
                                        @endif
                                    </td>

                                    {{-- Due Date --}}
                                    <td class="whitespace-nowrap px-4 py-3 text-xs">
                                        @if($borrow->due_date)
                                            <span class="font-semibold {{ $isOverdue ? 'text-rose-600' : 'text-slate-800' }}">
                                                {{ $borrow->due_date->locale('th')->translatedFormat('j M Y') }}
                                            </span>
                                            @if($borrow->renew_count > 0)
                                                <span class="ml-1 inline-block rounded-full bg-purple-50 px-1.5 py-0.5 text-[10px] font-medium text-purple-600 border border-purple-200">
                                                    ต่อ {{ $borrow->renew_count }}/2
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-slate-400 italic">ยังไม่กำหนด</span>
                                        @endif
                                    </td>

                                    {{-- Status Badge --}}
                                    <td class="whitespace-nowrap px-4 py-3 text-center">
                                        @if($borrow->status === 'pending')
                                            <span class="inline-flex items-center rounded-full bg-amber-50 px-2 py-0.5 text-[11px] font-medium text-amber-700 border border-amber-200">
                                                รออนุมัติ
                                            </span>
                                        @elseif($borrow->status === 'rejected')
                                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-600 border border-slate-200">
                                                ไม่อนุมัติ
                                            </span>
                                        @elseif($borrow->status === 'returned')
                                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-medium text-emerald-700 border border-emerald-200">
                                                คืนแล้ว
                                            </span>
                                        @elseif($isOverdue)
                                            <span class="inline-flex items-center rounded-full bg-rose-100 px-2 py-0.5 text-[11px] font-semibold text-rose-700 border border-rose-200 animate-pulse">
                                                เกินกำหนด
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-sky-50 px-2 py-0.5 text-[11px] font-medium text-sky-700 border border-sky-200">
                                                กำลังยืม
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Action Buttons --}}
                                    <td class="whitespace-nowrap px-4 py-3 text-right">
                                        @if(Auth::user()->role === 'admin' && $borrow->status === 'pending')
                                            <div class="flex items-center justify-end gap-1">
                                                <form action="{{ route('borrows.approve', $borrow) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center rounded-md bg-emerald-600 px-2 py-1 text-[11px] font-medium text-white shadow-xs transition hover:bg-emerald-700">
                                                        อนุมัติ
                                                    </button>
                                                </form>
                                                <form action="{{ route('borrows.reject', $borrow) }}" method="POST" onsubmit="return confirm('ยืนยันการปฏิเสธคำขอนี้หรือไม่?');">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center rounded-md border border-rose-200 bg-rose-50 px-2 py-1 text-[11px] font-medium text-rose-700 transition hover:bg-rose-100">
                                                        ปฏิเสธ
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif(Auth::user()->role === 'admin' && in_array($borrow->status, ['borrowed', 'overdue']))
                                            <form action="{{ route('borrows.return', $borrow) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center rounded-md bg-emerald-600 px-2.5 py-1 text-[11px] font-medium text-white shadow-xs transition hover:bg-emerald-700">
                                                    บันทึกการคืน
                                                </button>
                                            </form>
                                        @elseif($borrow->status === 'pending')
                                            <span class="text-[11px] font-medium text-amber-600">รอแอดมินอนุมัติ</span>
                                        @elseif(in_array($borrow->status, ['borrowed', 'overdue']))
                                            <div class="flex flex-col items-end gap-1">
                                                @if($borrow->canRenew())
                                                    <form action="{{ route('borrows.renew', $borrow) }}" method="POST" onsubmit="return confirm('ขยายเวลาการยืมเพิ่ม 7 วัน ใช่หรือไม่?');">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center rounded-md border border-purple-200 bg-purple-50 px-2 py-1 text-[11px] font-medium text-purple-700 transition hover:bg-purple-100">
                                                            ต่ออายุ (+7 วัน)
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-[11px] text-slate-400">ต่ออายุครบสิทธิ์แล้ว</span>
                                                @endif
                                            </div>
                                        @elseif($borrow->status === 'returned')
                                            <span class="text-[11px] text-slate-400">คืนเมื่อ {{ $borrow->returned_at?->locale('th')->translatedFormat('j M Y') }}</span>
                                        @else
                                            <span class="text-[11px] text-slate-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ Auth::user()->role === 'admin' ? 6 : 5 }}" class="px-6 py-16 text-center">
                                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-3xl">📥</div>
                                        <h3 class="mt-4 text-base font-bold text-slate-900">ยังไม่มีรายการยืม–คืนหนังสือ</h3>
                                        <p class="mt-1 text-xs text-slate-500">คำขอยืมหนังสือหรือประวัติการคืนจะแสดงที่นี่</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="pt-2">{{ $borrows->links() }}</div>

        </div>
    </div>
</x-app-layout>
