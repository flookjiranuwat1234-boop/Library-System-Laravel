<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-emerald-600">User Management</p>
                <h2 class="page-title text-slate-900">เพิ่มสมาชิกใหม่</h2>
            </div>
            <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                ← ยกเลิก
            </a>
        </div>
    </x-slot>

    <div class="page-shell">
        <div class="page-container max-w-2xl">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">ชื่อ-นามสกุล *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none">
                        @error('name')<p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">อีเมล *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none">
                        @error('email')<p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">สิทธิ์การใช้งาน (Role) *</label>
                        <select name="role" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none">
                            <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>สมาชิกทั่วไป (User)</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>ผู้ดูแลระบบ (Admin)</option>
                        </select>
                        @error('role')<p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">รหัสผ่าน *</label>
                        <input type="password" name="password" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none">
                        @error('password')<p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">ยืนยันรหัสผ่าน *</label>
                        <input type="password" name="password_confirmation" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div class="pt-4 border-t flex items-center justify-end gap-3">
                        <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">ยกเลิก</a>
                        <button type="submit" class="rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-emerald-700 shadow-md">บันทึกสมาชิก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
