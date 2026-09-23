<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Audit Logs</p>
                <h2 class="page-title text-slate-900">บันทึกกิจกรรมการใช้งาน</h2>
                <p class="text-xs text-slate-500 mt-0.5">ประวัติการทำรายการ แก้ไข ลบ หรือเปลี่ยนแปลงข้อมูลในระบบโดยผู้ใช้</p>
            </div>
            <p class="text-sm font-semibold text-slate-500">พบทั้งหมด {{ $logs->total() }} รายการ</p>
        </div>
    </x-slot>

    <div class="page-shell"><div class="page-container space-y-5">

        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="panel p-4 flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">ชื่อ Action</label>
                <input type="text" name="action" value="{{ $action }}" placeholder="เช่น book.created" class="form-control text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">สมาชิก</label>
                <select name="user_id" class="form-control text-sm">
                    <option value="">ทุกคน</option>
                    @foreach($users as $u)<option value="{{ $u->id }}" @selected($userId === $u->id)>{{ $u->name }}</option>@endforeach
                </select>
            </div>
            <button type="submit" class="button-primary text-sm px-5">กรอง</button>
            @if($action || $userId)<a href="{{ route('admin.activity-logs.index') }}" class="button-secondary text-sm px-5">ล้าง</a>@endif
        </form>

        <div class="panel overflow-hidden"><div class="overflow-x-auto"><table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50"><tr>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">เวลา</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">ผู้ทำรายการ</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Action</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">รายละเอียด</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">IP</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/80">
                        <td class="whitespace-nowrap px-4 py-3 text-xs text-slate-500">{{ $log->created_at->locale('th')->translatedFormat('j M Y H:i') }}</td>
                        <td class="px-4 py-3 text-sm text-slate-700">{{ $log->user?->name ?? '<ระบบ>' }}</td>
                        <td class="px-4 py-3">
                            @php
                                $actionColor = match(true) {
                                    str_contains($log->action, 'created') || str_contains($log->action, 'requested') => 'bg-emerald-100 text-emerald-800',
                                    str_contains($log->action, 'deleted') || str_contains($log->action, 'rejected') => 'bg-rose-100 text-rose-800',
                                    str_contains($log->action, 'updated') || str_contains($log->action, 'approved') || str_contains($log->action, 'renewed') => 'bg-sky-100 text-sky-800',
                                    str_contains($log->action, 'returned') => 'bg-violet-100 text-violet-800',
                                    default => 'bg-slate-100 text-slate-600',
                                };
                            @endphp
                            <span class="status-badge {{ $actionColor }}">{{ $log->action }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-700 max-w-xs truncate">{{ $log->description }}</td>
                        <td class="px-4 py-3 text-xs text-slate-400">{{ $log->ip_address }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-12 text-center text-sm text-slate-500">ยังไม่มีบันทึกกิจกรรม</td></tr>
                @endforelse
            </tbody>
        </table></div></div>
        <div>{{ $logs->links() }}</div>
    </div></div>
</x-app-layout>
