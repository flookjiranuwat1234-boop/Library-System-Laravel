<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Catalog Management</p>
                <h2 class="page-title text-slate-900">แก้ไขข้อมูลหนังสือ</h2>
                <p class="text-xs text-slate-500 mt-0.5">ปรับปรุงข้อมูลรายละเอียดหนังสือ รูปภาพปก หรือจำนวนในคลังความรู้</p>
            </div>
            <a href="{{ route('books.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 shadow-sm transition">
                ← ยกเลิก
            </a>
        </div>
    </x-slot>

    <div class="page-shell">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="panel p-5 sm:p-8">
                <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 mb-4">
                        <div>
                            <label for="title" class="block text-sm font-semibold text-slate-700">ชื่อหนังสือ <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}" class="form-control mt-1" required>
                            @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="author" class="block text-sm font-semibold text-slate-700">ผู้แต่ง <span class="text-rose-500">*</span></label>
                            <input type="text" name="author" id="author" value="{{ old('author', $book->author) }}" class="form-control mt-1" required>
                            @error('author')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="category_id" class="block text-sm font-semibold text-slate-700">หมวดหมู่ <span class="text-rose-500">*</span></label>
                            <select name="category_id" id="category_id" class="form-control mt-1" required>
                                <option value="">เลือกหมวดหมู่</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected((int) old('category_id', $book->category_id) === $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="stock" class="block text-sm font-semibold text-slate-700">จำนวนที่พร้อมให้ยืม <span class="text-rose-500">*</span></label>
                            <input type="number" name="stock" id="stock" min="0" value="{{ old('stock', $book->stock) }}" class="form-control mt-1" required>
                            @error('stock')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Extra info --}}
                    <div class="mb-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="mb-3 text-sm font-semibold text-slate-600">ข้อมูลเพิ่มเติม (ไม่บังคับ)</p>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-4">
                            <div>
                                <label for="isbn" class="block text-sm font-semibold text-slate-700">ISBN</label>
                                <input type="text" name="isbn" id="isbn" value="{{ old('isbn', $book->isbn) }}" placeholder="เช่น 9789740216..." class="form-control mt-1">
                                @error('isbn')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="publisher" class="block text-sm font-semibold text-slate-700">สำนักพิมพ์</label>
                                <input type="text" name="publisher" id="publisher" value="{{ old('publisher', $book->publisher) }}" class="form-control mt-1">
                                @error('publisher')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="year" class="block text-sm font-semibold text-slate-700">ปีพิมพ์</label>
                                <input type="number" name="year" id="year" value="{{ old('year', $book->year) }}" min="1000" max="{{ date('Y') + 1 }}" placeholder="เช่น 2567" class="form-control mt-1">
                                @error('year')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="pages" class="block text-sm font-semibold text-slate-700">จำนวนหน้า</label>
                                <input type="number" name="pages" id="pages" value="{{ old('pages', $book->pages) }}" min="1" placeholder="เช่น 320" class="form-control mt-1">
                                @error('pages')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
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

                    <div class="flex items-center justify-between">
                        <a href="{{ route('books.qr', $book) }}" target="_blank" class="text-sm font-semibold text-violet-600 hover:text-violet-800">🔲 ดู QR Code</a>
                        <div class="flex gap-2">
                            <a href="{{ route('books.index') }}" class="button-secondary me-2">ยกเลิก</a>
                            <button type="submit" class="button-primary">บันทึกการแก้ไข</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
