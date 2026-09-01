<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">
            {{ $book->title }}
        </h2>
    </x-slot>

    <div class="page-shell">
        <div class="page-container">
            <div class="panel p-5 sm:p-8">
                
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

                <div class="flex flex-col md:flex-row gap-8">
                    <div class="md:w-1/3">
                        @if($book->coverImageUrl())
                            <img src="{{ $book->coverImageUrl() }}" alt="{{ $book->title }}" class="w-full rounded shadow-lg">
                        @else
                            <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded shadow-lg text-gray-500">ไม่มีรูปปก</div>
                        @endif
                    </div>
                    
                    <div class="md:w-2/3">
                        <h1 class="text-3xl font-bold mb-2">{{ $book->title }}</h1>
                        <p class="text-xl text-gray-600 mb-4">ผู้แต่ง: {{ $book->author }}</p>
                        
                        <div class="mb-4">
                            <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">{{ $book->category->name ?? 'ไม่ระบุหมวดหมู่' }}</span>
                            <span class="inline-block {{ $book->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded-full px-3 py-1 text-sm font-semibold mr-2 mb-2">
                                คงเหลือ: {{ $book->stock }} เล่ม
                            </span>
                        </div>

                        <div class="prose max-w-none mb-6">
                            <h3 class="text-lg font-semibold mb-2">รายละเอียด</h3>
                            <p>{{ $book->description ?: 'ยังไม่มีรายละเอียดหนังสือ' }}</p>
                        </div>

                        <div class="mt-8 border-t pt-6">
                            @if($hasActiveBorrow)
                                <div class="inline-flex items-center rounded-lg bg-amber-100 px-5 py-3 font-semibold text-amber-800">
                                    คุณกำลังยืมหนังสือเล่มนี้อยู่
                                </div>
                                <p class="mt-2 text-sm text-gray-500">ตรวจสอบวันครบกำหนดได้ที่รายการยืมของฉัน</p>
                            @elseif($book->stock > 0)
                                <form action="{{ route('borrows.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                                    <button type="submit" class="button-primary px-6 py-3">
                                        ยืมหนังสือเล่มนี้
                                    </button>
                                </form>
                            @else
                                <button disabled class="bg-gray-400 text-white font-bold py-3 px-6 rounded-lg cursor-not-allowed">
                                    หนังสือถูกยืมหมดแล้ว
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
