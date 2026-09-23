<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">System Notifications</p>
                <h2 class="page-title text-slate-900">การแจ้งเตือนระบบ</h2>
                <p class="text-xs text-slate-500 mt-0.5">รายการแจ้งเตือนการยืม คืน และการอนุมัติคำขอหนังสือในระบบ</p>
            </div>
            <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                @csrf
                <button class="button-secondary text-xs">อ่านทั้งหมด</button>
            </form>
        </div>
    </x-slot>
    <div class="page-shell"><div class="page-container space-y-4">
        @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif
        @forelse($notifications as $notification)
            <article class="panel p-5 {{ $notification->read_at ? '' : 'border-emerald-300 bg-emerald-50/40' }}">
                <div class="flex items-start gap-4">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl {{ ($notification->data['type'] ?? '') === 'borrow_request' ? 'bg-sky-100 text-sky-700' : 'bg-amber-100 text-amber-700' }}">{{ ($notification->data['type'] ?? '') === 'borrow_request' ? '📖' : '⚠' }}</span>
                    <div class="min-w-0 flex-1"><div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between"><h3 class="font-bold text-slate-900">{{ $notification->data['title'] ?? 'การแจ้งเตือน' }}</h3><time class="whitespace-nowrap text-xs text-slate-400">{{ $notification->created_at->locale('th')->diffForHumans() }}</time></div><p class="mt-1 text-sm text-slate-600">{{ $notification->data['message'] ?? '' }}</p>@if(isset($notification->data['url']))<a href="{{ $notification->data['url'] }}" class="mt-3 inline-flex text-sm font-semibold text-emerald-700 hover:text-emerald-900">เปิดรายการเพื่อดำเนินการ →</a>@endif</div>
                </div>
            </article>
        @empty
            <div class="panel p-10 text-center text-slate-500">ยังไม่มีประวัติการแจ้งเตือน</div>
        @endforelse
        {{ $notifications->links() }}
    </div></div>
</x-app-layout>
