<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">
            {{ __('Books') }}
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
                    <div class="mb-4 rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <form method="GET" action="{{ route('books.index') }}" class="grid flex-1 grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_14rem_auto]">
                        <div>
                            <label for="search" class="block text-sm font-semibold text-slate-700">ค้นหาในคลังหนังสือ</label>
                            <input id="search" name="search" type="search" value="{{ $search }}" placeholder="ชื่อหนังสือหรือผู้แต่ง" class="form-control mt-1">
                        </div>
                        <div>
                            <label for="category" class="block text-sm font-semibold text-slate-700">หมวดหมู่</label>
                            <select id="category" name="category" class="form-control mt-1">
                                <option value="">ทุกหมวดหมู่</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected($categoryId === $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="rounded-md bg-gray-800 px-4 py-2 font-semibold text-white hover:bg-gray-700">ค้นหา</button>
                    </form>
                    @if(Auth::user()->role === 'admin')
                    <a href="{{ route('books.create') }}" class="button-primary">
                        เพิ่มหนังสือ
                    </a>
                    @endif
                </div>

                <div class="table-shell">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ปก</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชื่อหนังสือ</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ผู้แต่ง</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">หมวดหมู่</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">คงเหลือ</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($books as $book)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($book->coverImageUrl())
                                        <img src="{{ $book->coverImageUrl() }}" alt="{{ $book->title }}" class="h-10 w-10 rounded object-cover">
                                    @else
                                        <div class="h-10 w-10 rounded bg-gray-200 flex items-center justify-center text-gray-500 text-xs">ไม่มีรูป</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $book->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $book->author }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $book->category->name ?? 'ไม่ระบุ' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $book->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $book->stock }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('books.show', $book) }}" class="me-3 font-medium text-emerald-600 hover:text-emerald-800">ดู</a>
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('books.edit', $book) }}" class="me-3 font-medium text-sky-600 hover:text-sky-800">แก้ไข</a>
                                        <form action="{{ route('books.destroy', $book) }}" method="POST" class="inline-block" onsubmit="return confirm('ยืนยันการลบหนังสือเล่มนี้หรือไม่?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">ลบ</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">ไม่พบหนังสือที่ตรงกับการค้นหา</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">{{ $books->links() }}</div>

            </div>
        </div>
    </div>
</x-app-layout>
