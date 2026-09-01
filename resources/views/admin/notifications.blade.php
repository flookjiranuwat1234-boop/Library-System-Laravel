<x-app-layout>
    <x-slot name="header"><div class="flex items-center justify-between gap-4"><h2 class="page-title">ประวัติการแจ้งเตือน</h2><form method="POST" action="{{ route('admin.notifications.read-all') }}">@csrf<button class="button-secondary">อ่านทั้งหมด</button></form></div></x-slot>
    <div class="page-shell"><div class="page-container space-y-4">
        @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif
        @forelse($notifications as $notification)
            <article class="panel p-5 {{ $notification->read_at ? '' : 'border-emerald-300 bg-emerald-50/40' }}">
                <div class="flex items-start justify-between gap-4"><div><h3 class="font-bold text-slate-900">{{ $notification->data['title'] ?? 'การแจ้งเตือน' }}</h3><p class="mt-1 text-sm text-slate-600">{{ $notification->data['message'] ?? '' }}</p></div><time class="whitespace-nowrap text-xs text-slate-400">{{ $notification->created_at->locale('th')->diffForHumans() }}</time></div>
            </article>
        @empty
            <div class="panel p-10 text-center text-slate-500">ยังไม่มีประวัติการแจ้งเตือน</div>
        @endforelse
        {{ $notifications->links() }}
    </div></div>
</x-app-layout>
