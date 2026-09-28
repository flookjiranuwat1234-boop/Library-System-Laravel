<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Catalog Management</p>
                <h2 class="page-title text-slate-900">เพิ่มหนังสือเล่มใหม่</h2>
                <p class="text-xs text-slate-500 mt-0.5">กรอกรายละเอียดหนังสือ อัปโหลดรูปปก และกำหนดจำนวนในคลังความรู้</p>
            </div>
            <a href="{{ route('books.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 shadow-sm transition">
                ← ยกเลิก
            </a>
        </div>
    </x-slot>

    <div class="page-shell">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="panel p-5 sm:p-8">
                <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 mb-4">
                        <div>
                            <label for="title" class="block text-sm font-semibold text-slate-700">ชื่อหนังสือ <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control mt-1" required>
                            @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="author" class="block text-sm font-semibold text-slate-700">ผู้แต่ง <span class="text-rose-500">*</span></label>
                            <input type="text" name="author" id="author" value="{{ old('author') }}" class="form-control mt-1" required>
                            @error('author')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="category_id" class="block text-sm font-semibold text-slate-700">หมวดหมู่ <span class="text-rose-500">*</span></label>
                            <select name="category_id" id="category_id" class="form-control mt-1" required>
                                <option value="">เลือกหมวดหมู่</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected((int) old('category_id') === $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="stock" class="block text-sm font-semibold text-slate-700">จำนวนที่พร้อมให้ยืม <span class="text-rose-500">*</span></label>
                            <input type="number" name="stock" id="stock" min="0" value="{{ old('stock', 1) }}" class="form-control mt-1" required>
                            @error('stock')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Extra info --}}
                    <div class="mb-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="mb-3 text-sm font-semibold text-slate-600">ข้อมูลเพิ่มเติม (ไม่บังคับ)</p>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-4">
                            <div>
                                <label for="isbn" class="block text-sm font-semibold text-slate-700">ISBN</label>
                                <input type="text" name="isbn" id="isbn" value="{{ old('isbn') }}" placeholder="เช่น 9789740216..." class="form-control mt-1">
                                @error('isbn')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="publisher" class="block text-sm font-semibold text-slate-700">สำนักพิมพ์</label>
                                <input type="text" name="publisher" id="publisher" value="{{ old('publisher') }}" class="form-control mt-1">
                                @error('publisher')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="year" class="block text-sm font-semibold text-slate-700">ปีพิมพ์</label>
                                <input type="number" name="year" id="year" value="{{ old('year') }}" min="1000" max="{{ date('Y') + 1 }}" placeholder="เช่น 2567" class="form-control mt-1">
                                @error('year')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="pages" class="block text-sm font-semibold text-slate-700">จำนวนหน้า</label>
                                <input type="number" name="pages" id="pages" value="{{ old('pages') }}" min="1" placeholder="เช่น 320" class="form-control mt-1">
                                @error('pages')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Cover Image Upload with Live Preview --}}
                    <div class="mb-6 rounded-2xl border border-slate-200/80 bg-slate-50/50 p-4 sm:p-5" x-data="{
                        previewUrl: '',
                        handleFileChange(event) {
                            const file = event.target.files[0];
                            if (file) {
                                this.previewUrl = URL.createObjectURL(file);
                            }
                        }
                    }">
                        <label class="block text-sm font-bold text-slate-800 mb-2">รูปภาพหน้าปกหนังสือ (ไม่บังคับ)</label>
                        
                        <div class="flex flex-col sm:flex-row items-start gap-5">
                            {{-- Live Preview Box --}}
                            <div class="relative h-44 w-32 shrink-0 overflow-hidden rounded-2xl bg-slate-900 shadow-md border border-slate-200">
                                <template x-if="previewUrl">
                                    <div class="h-full w-full">
                                        <img :src="previewUrl" alt="" aria-hidden="true" class="absolute inset-0 h-full w-full object-cover blur-sm opacity-40 scale-110">
                                        <img :src="previewUrl" alt="ตัวอย่างรูปปก" class="relative z-10 h-full w-full object-contain p-1.5">
                                    </div>
                                </template>
                                <template x-if="!previewUrl">
                                    <div class="flex h-full w-full flex-col items-center justify-center p-3 text-center text-slate-400">
                                        <svg class="h-8 w-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span class="text-[11px]">ไม่มีรูปภาพ</span>
                                    </div>
                                </template>
                            </div>

                            {{-- File Upload Inputs --}}
                            <div class="flex-1 space-y-3 w-full">
                                <div>
                                    <label for="cover_image" class="block text-xs font-semibold text-slate-600 mb-1">เลือกไฟล์รูปภาพจากอุปกรณ์ (JPG, PNG, WebP ขนาดไม่เกิน 2MB)</label>
                                    <input type="file" name="cover_image" id="cover_image" accept="image/jpeg,image/png,image/webp" @change="handleFileChange($event)" class="form-control file:me-4 file:rounded-xl file:border-0 file:bg-emerald-600 file:px-4 file:py-2 file:text-xs file:font-bold file:text-white hover:file:bg-emerald-700 file:cursor-pointer file:transition">
                                    @error('cover_image')<p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>@enderror
                                </div>
                                <p class="text-[11px] text-slate-500">💡 เมื่อเลือกไฟล์ภาพ ระบบจะแสดงตัวอย่างให้เห็นทันที</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-semibold text-slate-700">รายละเอียด</label>
                        <textarea name="description" id="description" rows="4" class="form-control mt-1">{{ old('description') }}</textarea>
                        @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('books.index') }}" class="button-secondary me-2">ยกเลิก</a>
                        <button type="submit" class="button-primary">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
