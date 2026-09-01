<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">
            {{ __('Edit Category') }}
        </h2>
    </x-slot>

    <div class="page-shell">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            <div class="panel p-5 sm:p-8">
                <form action="{{ route('categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-semibold text-slate-700">ชื่อหมวดหมู่</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" class="form-control mt-1" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <a href="{{ route('categories.index') }}" class="button-secondary me-2">ยกเลิก</a>
                        <button type="submit" class="button-primary">บันทึกการแก้ไข</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
