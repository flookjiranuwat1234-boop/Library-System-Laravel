<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-emerald-600">User Management</p>
                <h2 class="page-title text-slate-900">แก้ไขข้อมูลสมาชิก</h2>
            </div>
            <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                ← ยกเลิก
            </a>
        </div>
    </x-slot>

    <div class="page-shell">
        <div class="page-container max-w-2xl">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">ชื่อ-นามสกุล *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none">
                        @error('name')<p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">อีเมล *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none">
                        @error('email')<p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">สิทธิ์การใช้งาน (Role) *</label>
                        <select name="role" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none">
                            <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>สมาชิกทั่วไป (User)</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>ผู้ดูแลระบบ (Admin)</option>
                        </select>
                        @error('role')<p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>@enderror
                    </div>

                    <div class="rounded-xl border border-amber-200 bg-amber-50/60 p-4 space-y-3">
                        <p class="text-xs font-bold text-amber-900">🔒 เปลี่ยนรหัสผ่านใหม่ (หากไม่ต้องการเปลี่ยนให้เว้นว่างไว้)</p>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">รหัสผ่านใหม่</label>
                            <input type="password" name="password" class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none bg-white">
                            @error('password')<p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">ยืนยันรหัสผ่านใหม่</label>
                            <input type="password" name="password_confirmation" class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none bg-white">
                        </div>
                    </div>

                    <div class="pt-4 border-t flex items-center justify-end gap-3">
                        <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">ยกเลิก</a>
                        <button type="submit" class="rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-emerald-700 shadow-md">บันทึกการเปลี่ยนแปลง</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
