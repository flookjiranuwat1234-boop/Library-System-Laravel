<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="page-title">
                {{ __('Admin Dashboard') }}
            </h2>
            <span class="text-sm text-gray-500">ระบบจัดการห้องสมุด</span>
        </div>
    </x-slot>

    <div class="page-shell">
        <div class="page-container space-y-8">
            <!-- Key Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Books -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747S17.5 6.253 12 6.253z" />
                            </svg>
                        </div>
                        <div class="ms-4">
                            <div class="text-sm font-medium text-gray-500">หนังสือทั้งหมด</div>
                            <div class="text-2xl font-bold text-gray-900">{{ $totalBooks }}</div>
                        </div>
                    </div>
                </div>

                <!-- Available Books -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ms-4">
                            <div class="text-sm font-medium text-gray-500">พร้อมให้ยืม</div>
                            <div class="text-2xl font-bold text-green-600">{{ $availableBooks }}</div>
                        </div>
                    </div>
                </div>

                <!-- Active Loans -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ms-4">
                            <div class="text-sm font-medium text-gray-500">กำลังยืม</div>
                            <div class="text-2xl font-bold text-indigo-600">{{ $activeLoans }}</div>
                        </div>
                    </div>
                </div>

                <!-- Overdue Books -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ms-4">
                            <div class="text-sm font-medium text-gray-500">เกินกำหนด</div>
                            <div class="text-2xl font-bold text-red-600">{{ $overdueLoans }}</div>
                        </div>
                    </div>
                </div>

                <!-- Total Members -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ms-4">
                            <div class="text-sm font-medium text-gray-500">สมาชิก</div>
                            <div class="text-2xl font-bold text-purple-600">{{ $totalMembers }}</div>
                        </div>
                    </div>
                </div>

                <!-- Categories -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                        </div>
                        <div class="ms-4">
                            <div class="text-sm font-medium text-gray-500">หมวดหมู่</div>
                            <div class="text-2xl font-bold text-yellow-600">{{ $totalCategories }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Low Stock Alert -->
                @if($lowStockBooks->count() > 0)
                <div class="bg-yellow-50 overflow-hidden shadow-sm sm:rounded-lg border border-yellow-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="h-5 w-5 text-yellow-600 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2m6-8a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            หนังสือใกล้หมด
                        </h3>
                        <div class="space-y-3">
                            @foreach($lowStockBooks as $book)
                            <div class="flex justify-between items-center p-3 bg-white rounded border border-yellow-100">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $book->title }}</p>
                                    <p class="text-sm text-gray-600">{{ $book->author }}</p>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-200 text-yellow-800">
                                    เหลือ {{ $book->stock }} เล่ม
                                </span>
                            </div>
                            @endforeach
                        </div>
                        <a href="{{ route('books.index') }}" class="mt-4 inline-block text-sm font-medium text-yellow-700 hover:text-yellow-900">
                            จัดการหนังสือ →
                        </a>
                    </div>
                </div>
                @endif

                <!-- Overdue Books Alert -->
                @if($overdueBooks->count() > 0)
                <div class="bg-red-50 overflow-hidden shadow-sm sm:rounded-lg border border-red-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="h-5 w-5 text-red-600 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            หนังสือเกินกำหนด
                        </h3>
                        <div class="space-y-3">
                            @foreach($overdueBooks->take(5) as $record)
                            <div class="flex justify-between items-start p-3 bg-white rounded border border-red-100">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $record->book->title }}</p>
                                    <p class="text-sm text-gray-600">{{ $record->user->name }} · ครบกำหนด {{ $record->due_date->locale('th')->translatedFormat('j M Y') }}</p>
                                </div>
                                <a href="{{ route('borrows.index') }}" class="text-xs font-medium text-red-700 hover:text-red-900">
                                    จัดการ
                                </a>
                            </div>
                            @endforeach
                            @if($overdueBooks->count() > 5)
                            <p class="text-sm text-gray-600 text-center py-2">
                                อีก {{ $overdueBooks->count() - 5 }} รายการที่เกินกำหนด
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Borrows -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">การยืมล่าสุด</h3>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($recentBorrows as $borrow)
                        <div class="p-4 hover:bg-gray-50">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $borrow->book->title }}</p>
                                    <p class="text-sm text-gray-600">{{ $borrow->user->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $borrow->borrowed_at->locale('th')->diffForHumans() }}</p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    กำลังยืม
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="p-4 text-center text-gray-500">
                            ยังไม่มีการยืมล่าสุด
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Returns -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">การคืนล่าสุด</h3>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($recentReturns as $activity)
                        <div class="p-4 hover:bg-gray-50">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $activity->book->title }}</p>
                                    <p class="text-sm text-gray-600">{{ $activity->user->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $activity->returned_at?->locale('th')->diffForHumans() ?? 'รอดำเนินการ' }}</p>
                                </div>
                                @if($activity->status === 'returned')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    คืนแล้ว
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    เกินกำหนด
                                </span>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="p-4 text-center text-gray-500">
                            ยังไม่มีกิจกรรมล่าสุด
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">เมนูลัด</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('books.create') }}" class="button-primary py-3">
                        <svg class="h-5 w-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        เพิ่มหนังสือใหม่
                    </a>
                    <a href="{{ route('categories.create') }}" class="inline-flex items-center justify-center rounded-xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">
                        <svg class="h-5 w-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        เพิ่มหมวดหมู่
                    </a>
                    <a href="{{ route('borrows.index') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-800 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-900">
                        <svg class="h-5 w-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                        จัดการยืม–คืน
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
