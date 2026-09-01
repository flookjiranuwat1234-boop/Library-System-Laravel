<x-app-layout>
    <x-slot name="header"><div class="flex flex-wrap items-end justify-between gap-4"><div><p class="text-sm font-semibold text-emerald-600">จัดระเบียบคลัง</p><h2 class="page-title">หมวดหมู่หนังสือ</h2></div><a href="{{ route('categories.create') }}" class="button-primary">+ เพิ่มหมวดหมู่</a></div></x-slot>
    <div class="page-shell"><div class="page-container space-y-6">
        @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif @if(session('error'))<div class="alert-error">{{ session('error') }}</div>@endif
        <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($categories as $category)
                <article class="panel group p-5 transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md"><div class="flex items-start justify-between gap-3"><span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-100 text-xl">🏷️</span><span class="status-badge bg-slate-100 text-slate-600">#{{ $category->id }}</span></div><h3 class="mt-5 text-lg font-bold text-slate-900">{{ $category->name }}</h3><p class="mt-1 text-sm text-slate-500">มีหนังสือ {{ $category->books_count }} รายการ</p><div class="mt-5 flex gap-4 border-t border-slate-100 pt-4"><a href="{{ route('categories.edit', $category) }}" class="text-sm font-semibold text-sky-600 hover:text-sky-800">แก้ไข</a><form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('ยืนยันการลบหมวดหมู่นี้หรือไม่?');">@csrf @method('DELETE')<button class="text-sm font-semibold text-rose-600 hover:text-rose-800">ลบ</button></form></div></article>
            @empty
                <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white p-12 text-center"><div class="text-4xl">🏷️</div><p class="mt-3 font-semibold text-slate-700">ยังไม่มีหมวดหมู่</p></div>
            @endforelse
        </section>
    </div></div>
</x-app-layout>
