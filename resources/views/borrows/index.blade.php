<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">
            {{ Auth::user()->role === 'admin' ? __('Borrow Records (Admin)') : __('My Borrows') }}
        </h2>
    </x-slot>

    <div class="page-shell">
        <div class="page-container">
            <div class="panel p-5 sm:p-6">
                
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="table-shell">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">หนังสือ</th>
                                @if(Auth::user()->role === 'admin')
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สมาชิก</th>
                                @endif
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">วันที่ยืม</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">วันครบกำหนด</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สถานะ</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($borrows as $borrow)
                            @php($isOverdue = $borrow->status !== 'returned' && $borrow->due_date->isPast())
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <a href="{{ route('books.show', $borrow->book) }}" class="font-semibold text-emerald-600 hover:text-emerald-800">{{ $borrow->book->title }}</a>
                                </td>
                                @if(Auth::user()->role === 'admin')
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $borrow->user->name }}</td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $borrow->borrowed_at->locale('th')->translatedFormat('j M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $borrow->due_date->locale('th')->translatedFormat('j M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($borrow->status === 'returned')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">คืนแล้ว</span>
                                    @elseif($isOverdue)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">เกินกำหนด</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">กำลังยืม</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($borrow->status !== 'returned' && Auth::user()->role === 'admin')
                                        <form action="{{ route('borrows.return', $borrow) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="font-semibold text-emerald-600 hover:text-emerald-800">บันทึกการคืน</button>
                                        </form>
                                    @elseif($borrow->status !== 'returned')
                                        <span class="text-gray-400 italic">กรุณาคืนหนังสือที่เจ้าหน้าที่</span>
                                    @else
                                        <span class="text-gray-500">คืนเมื่อ {{ $borrow->returned_at->locale('th')->translatedFormat('j M Y') }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ Auth::user()->role === 'admin' ? 6 : 5 }}" class="px-6 py-4 text-center text-sm text-gray-500">ยังไม่มีประวัติการยืม</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">{{ $borrows->links() }}</div>

            </div>
        </div>
    </div>
</x-app-layout>
