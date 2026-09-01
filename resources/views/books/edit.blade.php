<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">
            {{ __('Edit Book') }}
        </h2>
    </x-slot>

    <div class="page-shell">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="panel p-5 sm:p-8">
                <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="title" class="block text-sm font-semibold text-slate-700">ชื่อหนังสือ</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}" class="form-control mt-1" required>
                            @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="author" class="block text-sm font-semibold text-slate-700">ผู้แต่ง</label>
                            <input type="text" name="author" id="author" value="{{ old('author', $book->author) }}" class="form-control mt-1" required>
                            @error('author')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="category_id" class="block text-sm font-semibold text-slate-700">หมวดหมู่</label>
                            <select name="category_id" id="category_id" class="form-control mt-1" required>
                                <option value="">เลือกหมวดหมู่</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected((int) old('category_id', $book->category_id) === $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="stock" class="block text-sm font-semibold text-slate-700">จำนวนที่พร้อมให้ยืม</label>
                            <input type="number" name="stock" id="stock" min="0" value="{{ old('stock', $book->stock) }}" class="form-control mt-1" required>
                            @error('stock')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="cover_image" class="block text-sm font-semibold text-slate-700">เปลี่ยนรูปปกหนังสือ</label>
                        @if($book->coverImageUrl())
                            <img src="{{ $book->coverImageUrl() }}" alt="รูปปกปัจจุบันของ {{ $book->title }}" class="mb-3 mt-2 h-32 w-24 rounded-xl object-cover shadow-sm">
                        @endif
                        <input type="file" name="cover_image" id="cover_image" accept="image/jpeg,image/png,image/webp" class="form-control mt-1 file:me-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:font-semibold file:text-emerald-700">
                        <p class="mt-1 text-xs text-slate-500">ไฟล์ใหม่จะแทนที่และลบรูปเดิมทันที รองรับไฟล์ขนาดไม่เกิน 2 MB</p>
                        @if($book->cover_image)
                            <label class="mt-3 inline-flex items-center gap-2 text-sm text-slate-600">
                                <input type="checkbox" name="remove_cover" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500" @checked(old('remove_cover'))>
                                ลบรูปปกปัจจุบัน
                            </label>
                        @endif
                        @error('cover_image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-semibold text-slate-700">รายละเอียด</label>
                        <textarea name="description" id="description" rows="4" class="form-control mt-1">{{ old('description', $book->description) }}</textarea>
                        @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div class="flex justify-end">
                        <a href="{{ route('books.index') }}" class="button-secondary me-2">ยกเลิก</a>
                        <button type="submit" class="button-primary">บันทึกการแก้ไข</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
