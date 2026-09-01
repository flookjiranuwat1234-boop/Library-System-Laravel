<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="page-shell">
        <div class="page-container space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="panel p-6">
                    <div class="text-sm text-gray-500">หนังสือทั้งหมด</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $totalBooks }}</div>
                </div>
                <div class="panel p-6">
                    <div class="text-sm text-gray-500">หนังสือที่พร้อมยืม</div>
                    <div class="mt-2 text-3xl font-bold text-green-600">{{ $availableBooks }}</div>
                </div>
                <div class="panel p-6">
                    <div class="text-sm text-gray-500">รายการที่กำลังยืม</div>
                    <div class="mt-2 text-3xl font-bold text-sky-600">{{ $activeBorrowings }}</div>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl bg-slate-900 p-6 text-white shadow-lg">
                <p class="text-lg font-semibold mb-2">{{ __('Welcome back, :name!', ['name' => Auth::user()->name]) }}</p>
                <p class="text-slate-300">ค้นหาหนังสือ ตรวจสอบรายการยืม และติดตามวันครบกำหนดได้จากระบบห้องสมุดแห่งนี้</p>
            </div>
        </div>
    </div>
</x-app-layout>
