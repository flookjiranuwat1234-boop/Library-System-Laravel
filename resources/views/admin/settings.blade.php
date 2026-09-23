<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">System Settings</p>
            <h2 class="page-title text-slate-900">ตั้งค่าระบบห้องสมุด</h2>
            <p class="text-xs text-slate-500 mt-0.5">กำหนดเกณฑ์แจ้งเตือนหนังสือใกล้หมดและการส่งอีเมลทดสอบระบบ</p>
        </div>
    </x-slot>
    <div class="page-shell"><div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 space-y-6">
        @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif
        <section class="panel p-6">
            <h3 class="text-lg font-bold text-slate-900">การแจ้งเตือนคลังหนังสือ</h3>
            <p class="mt-1 text-sm text-slate-500">กำหนดจำนวนที่ระบบจะเริ่มแจ้งเตือนผู้ดูแล</p>
            <form method="POST" action="{{ route('admin.settings.update') }}" class="mt-6 space-y-5">
                @csrf @method('PUT')
                <div><label for="low_stock_threshold" class="block text-sm font-semibold text-slate-700">เกณฑ์หนังสือใกล้หมด</label>
                    <input id="low_stock_threshold" name="low_stock_threshold" type="number" min="0" max="100" value="{{ old('low_stock_threshold', $lowStockThreshold) }}" class="form-control mt-1">
                    @error('low_stock_threshold')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
                <button class="button-primary">บันทึกการตั้งค่า</button>
            </form>
        </section>
        <section class="panel p-6">
            <h3 class="text-lg font-bold text-slate-900">ตรวจสอบระบบอีเมล</h3>
            <p class="mt-1 text-sm text-slate-500">ส่งอีเมลทดสอบไปยัง {{ auth()->user()->email }} ผ่าน Queue</p>
            <form method="POST" action="{{ route('admin.settings.test-email') }}" class="mt-5">@csrf<button class="button-secondary">ส่งอีเมลทดสอบ</button></form>
        </section>
    </div></div>
</x-app-layout>
