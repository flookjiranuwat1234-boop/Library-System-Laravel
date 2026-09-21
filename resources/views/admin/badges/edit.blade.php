<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-emerald-600">Badges Configuration</p>
                <h2 class="page-title text-slate-900">
                    {{ __('แก้ไขเหรียญรางวัล') }}: {{ $badge->name_th }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">แก้ไขข้อมูลและเงื่อนไขการได้รับเหรียญรางวัล</p>
            </div>
            <a href="{{ route('admin.badges.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 shadow-sm transition">
                ← ย้อนกลับ
            </a>
        </div>
    </x-slot>

    <div class="page-shell">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                
                @if($errors->any())
                    <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-rose-700 mb-6 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.badges.update', $badge) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">รหัสเหรียญ (Code Unique)</label>
                            <input type="text" name="code" value="{{ old('code', $badge->code) }}" required class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-sm" />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">ชื่อเหรียญรางวัล (ภาษาไทย)</label>
                            <input type="text" name="name_th" value="{{ old('name_th', $badge->name_th) }}" required class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-sm" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">ไอคอน Emoji</label>
                            <input type="text" name="icon" value="{{ old('icon', $badge->icon) }}" required class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-sm" />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">ระดับเหรียญ (Tier)</label>
                            <select name="tier" required class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-sm">
                                <option value="bronze" {{ old('tier', $badge->tier) == 'bronze' ? 'selected' : '' }}>Bronze (ทองแดง)</option>
                                <option value="silver" {{ old('tier', $badge->tier) == 'silver' ? 'selected' : '' }}>Silver (เงิน)</option>
                                <option value="gold" {{ old('tier', $badge->tier) == 'gold' ? 'selected' : '' }}>Gold (ทอง)</option>
                                <option value="special" {{ old('tier', $badge->tier) == 'special' ? 'selected' : '' }}>Special (พิเศษ)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">คำอธิบาย</label>
                        <textarea name="description" rows="2" required class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-sm">{{ old('description', $badge->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">ประเภทเงื่อนไขการปลดล็อก</label>
                            <select name="condition_type" required class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-sm">
                                <option value="books_read" {{ old('condition_type', $badge->condition_type) == 'books_read' ? 'selected' : '' }}>จำนวนหนังสือที่อ่านจบ (books_read)</option>
                                <option value="categories_explored" {{ old('condition_type', $badge->condition_type) == 'categories_explored' ? 'selected' : '' }}>จำนวนหมวดหมู่ที่สำรวจ (categories_explored)</option>
                                <option value="on_time_streak_weeks" {{ old('condition_type', $badge->condition_type) == 'on_time_streak_weeks' ? 'selected' : '' }}>Streak สัปดาห์ต่อเนื่อง (on_time_streak_weeks)</option>
                                <option value="manual" {{ old('condition_type', $badge->condition_type) == 'manual' ? 'selected' : '' }}>แอดมินมอบให้เป็นกรณีพิเศษ (manual)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">เป้าหมายตัวเลข</label>
                            <input type="number" name="condition_value" value="{{ old('condition_value', $badge->condition_value) }}" min="0" required class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-sm" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">ลำดับการแสดงผล (Sort Order)</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $badge->sort_order) }}" min="0" required class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-sm" />
                    </div>

                    <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.badges.index') }}" class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 shadow-sm">
                            ยกเลิก
                        </a>
                        <button type="submit" class="rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-bold text-slate-950 hover:bg-amber-400 shadow-sm transition">
                            บันทึกการแก้ไข
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
