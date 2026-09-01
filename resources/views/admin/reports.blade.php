<x-app-layout>
    <x-slot name="header"><div class="flex flex-wrap items-center justify-between gap-3"><h2 class="page-title">รายงานห้องสมุด</h2><div class="flex gap-2"><a href="{{ route('admin.reports.csv') }}" class="button-secondary">ดาวน์โหลด CSV / Excel</a><a href="{{ route('admin.reports.print') }}" target="_blank" class="button-primary">พิมพ์ / บันทึก PDF</a></div></div></x-slot>
    <div class="page-shell"><div class="page-container space-y-6">
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-5">@foreach(['หนังสือ'=>$totalBooks,'สมาชิก'=>$totalMembers,'หมวดหมู่'=>$totalCategories,'กำลังยืม'=>$activeLoans,'เกินกำหนด'=>$overdueLoans] as $label=>$value)<div class="panel p-5"><p class="text-sm text-slate-500">{{ $label }}</p><p class="mt-2 text-3xl font-bold text-slate-900">{{ $value }}</p></div>@endforeach</div>
        <section class="panel p-6"><h3 class="text-lg font-bold text-slate-900">หนังสือยอดนิยม</h3><div class="mt-4 divide-y divide-slate-100">@foreach($popularBooks as $book)<div class="flex justify-between gap-4 py-3"><span>{{ $book->title }}</span><span class="status-badge bg-emerald-100 text-emerald-800">{{ $book->borrow_records_count }} ครั้ง</span></div>@endforeach</div></section>
    </div></div>
</x-app-layout>
