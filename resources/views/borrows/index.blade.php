<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div><p class="text-sm font-semibold text-emerald-600">{{ Auth::user()->role === 'admin' ? 'งานบริการยืม–คืน' : 'พื้นที่สมาชิก' }}</p><h2 class="page-title">{{ Auth::user()->role === 'admin' ? 'คำขอและประวัติการยืม' : 'รายการยืมของฉัน' }}</h2></div>
            <p class="text-sm text-slate-500">พบ {{ $borrows->total() }} รายการ</p>
        </div>
    </x-slot>

    <div class="page-shell"><div class="page-container space-y-5">
        @if(session('success'))<div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800" role="alert">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-medium text-rose-800" role="alert">{{ session('error') }}</div>@endif

        <div class="panel overflow-hidden"><div class="overflow-x-auto"><table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50"><tr>
                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">หนังสือ</th>
                @if(Auth::user()->role === 'admin')<th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">สมาชิก</th>@endif
                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">วันที่ยืม</th>
                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">กำหนดคืน</th>
                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">สถานะ</th>
                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">จัดการ</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($borrows as $borrow)
                    @php($isOverdue = in_array($borrow->status, ['borrowed', 'overdue']) && $borrow->due_date?->isPast())
                    <tr class="transition hover:bg-slate-50/80">
                        <td class="px-6 py-4 text-sm"><a href="{{ route('books.show', $borrow->book) }}" class="font-semibold text-slate-900 hover:text-emerald-700">{{ $borrow->book->title }}</a></td>
                        @if(Auth::user()->role === 'admin')<td class="px-6 py-4 text-sm text-slate-700">{{ $borrow->user->name }}</td>@endif
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">{{ $borrow->borrowed_at?->locale('th')->translatedFormat('j M Y') ?? 'รออนุมัติ' }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">
                            {{ $borrow->due_date?->locale('th')->translatedFormat('j M Y') ?? 'ยังไม่กำหนด' }}
                            @if($borrow->renew_count > 0)<span class="ml-1 text-xs text-violet-500">(ต่อแล้ว {{ $borrow->renew_count }}/2)</span>@endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            @if($borrow->status === 'pending')<span class="status-badge bg-amber-100 text-amber-800">รออนุมัติ</span>
                            @elseif($borrow->status === 'rejected')<span class="status-badge bg-slate-100 text-slate-600">ไม่อนุมัติ</span>
                            @elseif($borrow->status === 'returned')<span class="status-badge bg-emerald-100 text-emerald-800">คืนแล้ว</span>
                            @elseif($isOverdue)<span class="status-badge bg-rose-100 text-rose-800">เกินกำหนด</span>
                            @else<span class="status-badge bg-sky-100 text-sky-800">กำลังยืม</span>@endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                            @if(Auth::user()->role === 'admin' && $borrow->status === 'pending')
                                <div class="flex gap-3"><form action="{{ route('borrows.approve', $borrow) }}" method="POST">@csrf<button type="submit" class="font-semibold text-emerald-600 hover:text-emerald-800">อนุมัติ</button></form><form action="{{ route('borrows.reject', $borrow) }}" method="POST" onsubmit="return confirm('ยืนยันการปฏิเสธคำขอนี้หรือไม่?');">@csrf<button type="submit" class="font-semibold text-rose-600 hover:text-rose-800">ปฏิเสธ</button></form></div>
                            @elseif(Auth::user()->role === 'admin' && in_array($borrow->status, ['borrowed', 'overdue']))
                                <form action="{{ route('borrows.return', $borrow) }}" method="POST">@csrf<button type="submit" class="font-semibold text-emerald-600 hover:text-emerald-800">บันทึกการคืน</button></form>
                            @elseif($borrow->status === 'pending')
                                <span class="text-slate-400">ผู้ดูแลกำลังตรวจสอบ</span>
                            @elseif(in_array($borrow->status, ['borrowed', 'overdue']))
                                <div class="flex flex-col gap-1">
                                    <span class="text-slate-400 text-xs">คืนหนังสือที่เคาน์เตอร์</span>
                                    @if($borrow->canRenew())
                                        <form action="{{ route('borrows.renew', $borrow) }}" method="POST" onsubmit="return confirm('ต่ออายุการยืม +7 วัน ใช่หรือไม่?');">
                                            @csrf
                                            <button type="submit" class="font-semibold text-violet-600 hover:text-violet-800 text-xs">🔄 ต่ออายุ (+7 วัน)</button>
                                        </form>
                                    @else
                                        <span class="text-slate-400 text-xs">ต่ออายุครบแล้ว</span>
                                    @endif
                                </div>
                            @elseif($borrow->status === 'returned')
                                <span class="text-slate-500">คืนเมื่อ {{ $borrow->returned_at?->locale('th')->translatedFormat('j M Y') }}</span>
                            @else<span class="text-slate-400">สิ้นสุดคำขอ</span>@endif
                        </td>
                    </tr>
                @empty<tr><td colspan="{{ Auth::user()->role === 'admin' ? 6 : 5 }}" class="px-6 py-14 text-center text-sm text-slate-500">ยังไม่มีคำขอหรือประวัติการยืม</td></tr>@endforelse
            </tbody>
        </table></div></div>
        <div>{{ $borrows->links() }}</div>
    </div></div>
</x-app-layout>
