<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-emerald-600">User Management</p>
                <h2 class="page-title text-slate-900">
                    {{ __('จัดการสมาชิก (Users & Admins)') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">รายชื่อสมาชิกทั้งหมด สิทธิ์การใช้งาน และประวัติการยืมหนังสือ</p>
            </div>
            <div>
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-emerald-700 transition">
                    + เพิ่มสมาชิกใหม่
                </a>
            </div>
        </div>
    </x-slot>

    <div class="page-shell">
        <div class="page-container space-y-6">

            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert-error">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Filter and Search Bar --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-center">
                        <div class="relative flex-1">
                            <input type="text" name="search" value="{{ $search }}" placeholder="ค้นหาด้วยชื่อ หรือ อีเมลสมาชิก..." class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2 text-sm text-slate-900 focus:border-emerald-500 focus:bg-white focus:outline-none">
                        </div>
                        <select name="role" class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2 text-sm text-slate-800 focus:border-emerald-500 focus:bg-white focus:outline-none">
                            <option value="">-- ทุกสิทธิ์การใช้งาน --</option>
                            <option value="user" {{ $role === 'user' ? 'selected' : '' }}>สมาชิกทั่วไป (User)</option>
                            <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>ผู้ดูแลระบบ (Admin)</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white hover:bg-slate-800 shadow-sm transition">
                            ค้นหา
                        </button>
                        @if($search || $role)
                            <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                                ล้างตัวกรอง
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Users Table --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/80 text-xs font-bold uppercase tracking-wider text-slate-500">
                                <th class="px-6 py-4">ผู้ใช้งาน</th>
                                <th class="px-6 py-4">สิทธิ์ใช้งาน</th>
                                <th class="px-6 py-4">ประวัติการยืม</th>
                                <th class="px-6 py-4">วันที่ลงทะเบียน</th>
                                <th class="px-6 py-4 text-right">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($users as $userItem)
                                <tr class="transition hover:bg-slate-50/60">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full font-bold text-sm shadow-xs {{ $userItem->role === 'admin' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                                                {{ mb_substr($userItem->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-900 text-sm">{{ $userItem->name }}</p>
                                                <p class="text-xs text-slate-400 font-mono">{{ $userItem->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-bold">
                                        @if($userItem->role === 'admin')
                                            <span class="inline-block rounded-full bg-amber-100 px-3 py-1 text-amber-800 border border-amber-200">
                                                ผู้ดูแลระบบ (Admin)
                                            </span>
                                        @else
                                            <span class="inline-block rounded-full bg-emerald-100 px-3 py-1 text-emerald-800 border border-emerald-200">
                                                สมาชิกทั่วไป (User)
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-600 font-medium">
                                        {{ $userItem->borrow_records_count }} รายการ
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-500">
                                        {{ $userItem->created_at->locale('th')->translatedFormat('j M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-xs">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.journey.show', $userItem) }}" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 font-semibold text-indigo-600 hover:bg-indigo-50 shadow-xs">
                                                สถิติ
                                            </a>
                                            <a href="{{ route('admin.users.edit', $userItem) }}" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 font-semibold text-slate-700 hover:bg-slate-50 shadow-xs">
                                                แก้ไข
                                            </a>
                                            @if($userItem->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $userItem) }}" method="POST" onsubmit="return confirm('ยืนยันที่จะลบผู้ใช้งาน {{ $userItem->name }} หรือไม่?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rounded-lg bg-rose-50 px-3 py-1.5 font-semibold text-rose-600 hover:bg-rose-100">
                                                        ลบ
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-400">
                                        ไม่พบข้อมูลสมาชิกในระบบ
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="border-t border-slate-100 p-4">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
