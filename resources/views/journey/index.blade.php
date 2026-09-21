<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-emerald-600">พื้นที่สมาชิก</p>
                <h2 class="page-title">เส้นทางการอ่านของฉัน</h2>
            </div>
            <p class="text-sm text-slate-500">{{ now()->locale('th')->translatedFormat('lที่ j F Y') }}</p>
        </div>
    </x-slot>

    <div class="page-shell">
        <div class="page-container space-y-8" x-data="{
            selectedBook: null,
            openBookModal(book, record, theme) {
                this.selectedBook = { ...book, record, theme };
                $dispatch('open-modal', 'book-details-modal');
            }
        }">
            <!-- Flash Message / Badge Toast -->
            @if(session('success'))
                <div class="alert-success">
                    <span class="text-lg">🎉</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- 1. Hero Stats -->
            <section class="relative overflow-hidden rounded-3xl bg-slate-950 px-6 py-8 text-white shadow-2xl shadow-slate-900/20 sm:px-8 lg:px-10">
                <div class="absolute -right-16 -top-20 h-64 w-64 rounded-full bg-emerald-500/20 blur-3xl"></div>
                <div class="absolute -left-16 -bottom-20 h-64 w-64 rounded-full bg-teal-500/15 blur-3xl"></div>

                <div class="relative space-y-6">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-500/10 px-4 py-1.5 text-sm font-medium text-emerald-300 backdrop-blur">
                            <span>✨</span>
                            <span>ระดับ {{ $stat->currentLevel() }} นักอ่านผู้มุ่งมั่น</span>
                        </div>
                        <div class="text-sm font-semibold text-emerald-400">
                            {{ $stat->total_points }} แต้มสะสม
                        </div>
                    </div>

                    <!-- 4 Main Counters -->
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 sm:gap-6">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4 sm:p-5 backdrop-blur transition hover:bg-white/10">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/20 text-xl text-emerald-400">📚</span>
                                <span class="text-xs sm:text-sm font-medium text-slate-300">อ่านจบแล้ว</span>
                            </div>
                            <p class="mt-3 text-2xl sm:text-3xl font-bold tracking-tight text-white" x-data="journeyCounter({{ $stat->total_books_read }})" x-text="displayValue">
                                {{ $stat->total_books_read }}
                            </p>
                            <span class="text-xs text-slate-400">เล่ม</span>
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4 sm:p-5 backdrop-blur transition hover:bg-white/10">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/20 text-xl text-amber-400">⭐</span>
                                <span class="text-xs sm:text-sm font-medium text-slate-300">แต้มความสำเร็จ</span>
                            </div>
                            <p class="mt-3 text-2xl sm:text-3xl font-bold tracking-tight text-white" x-data="journeyCounter({{ $stat->total_points }})" x-text="displayValue">
                                {{ $stat->total_points }}
                            </p>
                            <span class="text-xs text-slate-400">แต้ม</span>
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4 sm:p-5 backdrop-blur transition hover:bg-white/10">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-500/20 text-xl text-purple-400">🏆</span>
                                <span class="text-xs sm:text-sm font-medium text-slate-300">ตราที่ได้รับ</span>
                            </div>
                            <p class="mt-3 text-2xl sm:text-3xl font-bold tracking-tight text-white" x-data="journeyCounter({{ count($unlockedBadges) }})" x-text="displayValue">
                                {{ count($unlockedBadges) }}
                            </p>
                            <span class="text-xs text-slate-400">จาก {{ count($allBadges) }} ตรา</span>
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4 sm:p-5 backdrop-blur transition hover:bg-white/10">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-500/20 text-xl text-rose-400">🔥</span>
                                <span class="text-xs sm:text-sm font-medium text-slate-300">อ่านต่อเนื่อง</span>
                            </div>
                            <p class="mt-3 text-2xl sm:text-3xl font-bold tracking-tight text-white" x-data="journeyCounter({{ $stat->current_streak }})" x-text="displayValue">
                                {{ $stat->current_streak }}
                            </p>
                            <span class="text-xs text-slate-400">สัปดาห์ติดต่อกัน</span>
                        </div>
                    </div>

                    <!-- Level Progress Bar -->
                    <div class="pt-2">
                        <div class="flex items-center justify-between text-xs font-medium text-slate-300 mb-2">
                            <span>ความก้าวหน้าสู่ระดับ {{ $stat->currentLevel() + 1 }}</span>
                            <span class="text-emerald-400">อีก {{ $stat->pointsToNextLevel() }} แต้มถึงระดับถัดไป</span>
                        </div>
                        <div class="h-2.5 w-full overflow-hidden rounded-full bg-slate-800/80 p-0.5">
                            <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-400 transition-all duration-1000 ease-out" style="width: {{ $stat->pointsInCurrentLevel() }}%;"></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 2. ชั้นหนังสือส่วนตัว (Soft, Elegant Scandinavian Oak Bookshelf) -->
            <section class="panel p-0 overflow-hidden border border-amber-200/80 shadow-xl rounded-3xl bg-white" x-data="journeyReveal()">
                <!-- Soft Warm Bookshelf Header Bar -->
                <div class="bg-gradient-to-r from-[#fbf8f3] via-[#f5ede2] to-[#fbf8f3] px-6 py-5 sm:px-8 border-b border-amber-200/70 flex flex-wrap items-center justify-between gap-4 text-slate-800">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/15 text-2xl border border-amber-400/40 shadow-xs">
                            📚
                        </span>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                                <span>ชั้นหนังสือส่วนตัว</span>
                                <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-100/80 text-emerald-800 border border-emerald-300/60 font-medium">Personal Library</span>
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">คลังหนังสือทั้งหมดที่คุณเคยอ่านจบและส่งคืนแล้ว</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-emerald-800 bg-emerald-50 px-3.5 py-1.5 rounded-full border border-emerald-200 shadow-xs">
                        <span>📖</span>
                        <span>{{ $totalBooksRead }} เล่มบนชั้นวาง</span>
                    </div>
                </div>

                @if($shelves->isEmpty())
                    <!-- Empty State -->
                    <div class="bg-gradient-to-b from-[#fdfcf9] via-[#f7f3ec] to-[#f2ebd9] p-10 sm:p-16 text-center text-slate-700">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-amber-100/90 border border-amber-300 text-4xl shadow-inner">
                            📚
                        </div>
                        <h3 class="mt-4 text-lg font-bold text-slate-900">ชั้นหนังสือของคุณยังว่างอยู่</h3>
                        <p class="mt-1.5 text-sm text-slate-500 max-w-md mx-auto">เริ่มต้นยืมหนังสือเล่มแรกเพื่อบันทึกประวัติการอ่านและสร้างคอลเลกชันบนชั้นไม้ของคุณเอง</p>
                        <div class="mt-6">
                            <a href="{{ route('books.index') }}" class="button-primary">
                                สำรวจคลังหนังสือ
                            </a>
                        </div>

                        <!-- Illustrated Empty Shelf Plank -->
                        <div class="mt-10 max-w-xl mx-auto">
                            <div class="h-5 w-full bg-gradient-to-b from-amber-600 via-amber-700 to-amber-900 rounded-sm shadow-[inset_0_2px_3px_rgba(255,255,255,0.4),0_8px_16px_rgba(0,0,0,0.15)] border-t-2 border-amber-400/80 relative">
                                <div class="absolute -top-4 left-4 text-xl">🪴</div>
                                <div class="absolute -top-4 right-4 text-xl">⏳</div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Soft Warm Linen/Oak Interior (Clean, Soft & Elegant) -->
                    <div class="bg-gradient-to-b from-[#fcfaf7] via-[#f6f0e6] to-[#eee5d6] p-5 sm:p-8 space-y-10 relative">
                        @foreach($shelves as $shelfIndex => $shelfBooks)
                            <div class="relative pt-4">
                                <!-- Books Row with No Ugly Scrollbars ([scrollbar-width:none] [&::-webkit-scrollbar]:hidden) -->
                                <div class="flex items-end gap-1.5 sm:gap-2.5 px-4 sm:px-8 overflow-x-auto overflow-y-visible pt-8 pb-0 min-h-[185px] relative z-10 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
                                    
                                    <!-- Left Shelf Bookend Prop -->
                                    @if($shelfIndex === 0)
                                        <div class="shrink-0 flex flex-col items-center justify-end pb-1 pr-2 select-none pointer-events-none opacity-90">
                                            <span class="text-3xl filter drop-shadow-[0_4px_6px_rgba(0,0,0,0.2)]">🪴</span>
                                        </div>
                                    @elseif($shelfIndex === 1)
                                        <div class="shrink-0 flex flex-col items-center justify-end pb-1 pr-2 select-none pointer-events-none opacity-90">
                                            <span class="text-3xl filter drop-shadow-[0_4px_6px_rgba(0,0,0,0.2)]">🕰️</span>
                                        </div>
                                    @endif

                                    <!-- Books -->
                                    @foreach($shelfBooks as $item)
                                        @php
                                            $book = $item['book'];
                                            $record = $item['record'];
                                            $theme = $item['theme'];
                                        @endphp
                                        <button
                                            type="button"
                                            @click="openBookModal({{ json_encode($book) }}, {{ json_encode($record) }}, {{ json_encode($theme) }})"
                                            class="group relative shrink-0 cursor-pointer text-left transition-all duration-300 hover:-translate-y-3 hover:scale-105 hover:z-30 focus:outline-none focus:ring-2 focus:ring-amber-500 rounded-t-sm"
                                            style="height: {{ $item['height'] }}px; width: {{ $item['width'] }}px; transform: rotate({{ $item['tilt'] }}deg);"
                                            title="{{ $book->title }} โดย {{ $book->author }}"
                                        >
                                            <!-- Spine Body with 3D Curvature & Embossing -->
                                            <div
                                                class="relative h-full w-full overflow-hidden rounded-t-[3px] shadow-[inset_3px_0_6px_rgba(255,255,255,0.25),_inset_-3px_0_6px_rgba(0,0,0,0.35),_0_4px_8px_rgba(0,0,0,0.2)] group-hover:shadow-[inset_3px_0_6px_rgba(255,255,255,0.4),_0_12px_20px_rgba(0,0,0,0.35),_0_0_10px_rgba(245,158,11,0.4)] flex flex-col justify-between py-2 px-1 border-t border-r border-white/25"
                                                style="background: {{ $theme['bg_gradient'] }};"
                                            >
                                                <!-- Top Gold Foil Embossed Band -->
                                                <div class="space-y-0.5">
                                                    <div class="h-1 w-full rounded-full opacity-90" style="background: {{ $theme['foil_color'] }}; box-shadow: 0 0 2px {{ $theme['foil_color'] }};"></div>
                                                    <div class="h-0.5 w-full opacity-60 bg-white/40"></div>
                                                </div>

                                                <!-- Vertical Book Title -->
                                                <div class="flex-1 flex items-center justify-center overflow-hidden my-1 px-0.5">
                                                    <span
                                                        class="text-[11px] font-bold leading-none truncate [writing-mode:vertical-rl] tracking-wide select-none drop-shadow-[0_1px_2px_rgba(0,0,0,0.85)] max-h-[105px]"
                                                        style="color: {{ $theme['text_color'] }}; font-family: 'Noto Sans Thai', sans-serif;"
                                                    >
                                                        {{ $book->title }}
                                                    </span>
                                                </div>

                                                <!-- Bottom Gold Foil Embossed Band & Ribs -->
                                                <div class="space-y-0.5">
                                                    <div class="h-0.5 w-full opacity-60 bg-white/40"></div>
                                                    <div class="h-1 w-full rounded-full opacity-90" style="background: {{ $theme['foil_color'] }}; box-shadow: 0 0 2px {{ $theme['foil_color'] }};"></div>
                                                </div>
                                            </div>

                                            <!-- Hanging Bookmark Ribbon -->
                                            @if($item['has_ribbon'])
                                                <div
                                                    class="absolute -bottom-2.5 left-1/2 -translate-x-1/2 w-2 h-3.5 rounded-b-sm shadow-sm pointer-events-none z-20"
                                                    style="background: {{ $theme['ribbon_color'] }}; clip-path: polygon(0% 0%, 100% 0%, 100% 100%, 50% 75%, 0% 100%);"
                                                ></div>
                                            @endif
                                        </button>
                                    @endforeach

                                    <!-- Right Shelf Bookend Prop -->
                                    @if($shelfIndex === 0)
                                        <div class="shrink-0 flex flex-col items-center justify-end pb-1 pl-2 select-none pointer-events-none opacity-90">
                                            <span class="text-3xl filter drop-shadow-[0_4px_6px_rgba(0,0,0,0.2)]">🏆</span>
                                        </div>
                                    @elseif($shelfIndex === 1)
                                        <div class="shrink-0 flex flex-col items-center justify-end pb-1 pl-2 select-none pointer-events-none opacity-90">
                                            <span class="text-3xl filter drop-shadow-[0_4px_6px_rgba(0,0,0,0.2)]">☕</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Warm Golden Teak 3D Wooden Shelf Plank -->
                                <div class="relative z-20">
                                    <!-- Main Wood Plank with Bevel -->
                                    <div class="h-5 sm:h-6 w-full bg-gradient-to-b from-amber-600 via-amber-700 to-amber-900 rounded-sm shadow-[inset_0_2px_3px_rgba(255,255,255,0.4),_inset_0_-3px_6px_rgba(0,0,0,0.45),_0_8px_16px_rgba(120,53,15,0.25)] border-t-2 border-amber-300/80 border-b border-amber-950 flex items-center justify-between px-6">
                                        <!-- Wood Grain Highlight Line -->
                                        <div class="h-0.5 w-full bg-gradient-to-r from-transparent via-amber-200/40 to-transparent"></div>
                                    </div>

                                    <!-- Left & Right Vintage Brass Support Brackets -->
                                    <div class="flex justify-between px-6 -mt-0.5 pointer-events-none">
                                        <div class="w-3.5 h-3.5 bg-gradient-to-b from-amber-500 to-amber-800 rounded-b-sm shadow-xs border-t border-amber-300/60"></div>
                                        <div class="w-3.5 h-3.5 bg-gradient-to-b from-amber-500 to-amber-800 rounded-b-sm shadow-xs border-t border-amber-300/60"></div>
                                    </div>

                                    <!-- Soft Drop Shadow Under Shelf -->
                                    <div class="h-3 w-full bg-gradient-to-b from-amber-950/20 to-transparent -mt-2.5 blur-xs pointer-events-none"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <!-- 3. Reading Heatmap -->
            <section class="panel p-6 sm:p-8" x-data="journeyReveal()">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">ความสม่ำเสมอในการอ่าน</h2>
                        <p class="text-sm text-slate-500">บันทึกกิจกรรมการยืมหนังสือในรอบ 1 ปีที่ผ่านมา</p>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <span>น้อย</span>
                        <span class="h-3 w-3 rounded-xs bg-slate-100 border border-slate-200"></span>
                        <span class="h-3 w-3 rounded-xs bg-emerald-200"></span>
                        <span class="h-3 w-3 rounded-xs bg-emerald-400"></span>
                        <span class="h-3 w-3 rounded-xs bg-emerald-600"></span>
                        <span class="h-3 w-3 rounded-xs bg-emerald-800"></span>
                        <span>มาก</span>
                    </div>
                </div>

                <div class="relative overflow-x-auto pb-2">
                    <div class="min-w-[760px]">
                        <!-- Months Header -->
                        <div class="flex text-[11px] text-slate-400 mb-2 pl-6">
                            @foreach($heatmap['months'] as $month)
                                <div style="margin-left: {{ $loop->first ? ($month['col'] * 14) : (($month['col'] - $heatmap['months'][$loop->index - 1]['col'] - 1) * 14) }}px;">
                                    {{ $month['name'] }}
                                </div>
                            @endforeach
                        </div>

                        <!-- Days of week + Grid -->
                        <div class="flex gap-2">
                            <!-- Weekday Labels -->
                            <div class="flex flex-col justify-between text-[10px] text-slate-400 py-0.5 h-[98px]">
                                <span>จ.</span>
                                <span>พ.</span>
                                <span>ศ.</span>
                                <span>อา.</span>
                            </div>

                            <!-- 53 Columns of Weeks -->
                            <div class="flex gap-[3px]">
                                @foreach($heatmap['weeks'] as $week)
                                    <div class="flex flex-col gap-[3px]">
                                        @foreach($week as $day)
                                            <div
                                                class="h-[11px] w-[11px] rounded-[2px] transition-transform hover:scale-125 hover:z-10
                                                    @if($day['level'] === 0) bg-slate-100
                                                    @elseif($day['level'] === 1) bg-emerald-200
                                                    @elseif($day['level'] === 2) bg-emerald-400
                                                    @elseif($day['level'] === 3) bg-emerald-600
                                                    @else bg-emerald-800
                                                    @endif
                                                    @if($day['is_future']) opacity-25 @endif"
                                                title="{{ $day['thai_date'] }} · ยืม {{ $day['count'] }} เล่ม"
                                            ></div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 4. Badge Collection -->
            <section class="panel p-6 sm:p-8" x-data="journeyReveal()">
                <div class="flex items-center justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">ตราเกียรติยศ</h2>
                        <p class="text-sm text-slate-500">รางวัลความสำเร็จในการอ่านของคุณ</p>
                    </div>
                    <span class="text-sm font-semibold text-emerald-600">
                        ปลดล็อกแล้ว {{ count($unlockedBadges) }} / {{ count($allBadges) }}
                    </span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-4">
                    @foreach($allBadges as $badge)
                        @php
                            $isUnlocked = isset($unlockedBadges[$badge->id]);
                            $tierStyles = match($badge->tier) {
                                'bronze' => 'border-amber-600 bg-amber-50/50 text-amber-900 shadow-amber-500/10',
                                'silver' => 'border-slate-400 bg-slate-100/60 text-slate-800 shadow-slate-500/10',
                                'gold' => 'border-yellow-500 bg-yellow-50/60 text-yellow-900 shadow-yellow-500/15',
                                'platinum' => 'border-violet-500 bg-violet-50/60 text-violet-900 shadow-violet-500/15',
                                default => 'border-emerald-500 bg-emerald-50/50 text-emerald-900',
                            };
                        @endphp

                        <div class="group relative rounded-2xl border p-4 text-center transition-all duration-200 hover:-translate-y-1 hover:shadow-lg
                            {{ $isUnlocked ? $tierStyles.' border-2 shadow-md' : 'border-dashed border-slate-200 bg-slate-50/60 opacity-60' }}"
                        >
                            <!-- Badge Icon -->
                            <div class="relative mx-auto flex h-14 w-14 items-center justify-center rounded-2xl text-3xl shadow-inner
                                {{ $isUnlocked ? 'bg-white' : 'bg-slate-200/80 grayscale' }}"
                            >
                                <span>{{ $badge->icon }}</span>
                                @if(! $isUnlocked)
                                    <span class="absolute -bottom-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-slate-800 text-[10px] text-white">🔒</span>
                                @endif
                            </div>

                            <h3 class="mt-3 text-sm font-bold text-slate-900 line-clamp-1">{{ $badge->name_th }}</h3>
                            <p class="mt-1 text-xs text-slate-500 line-clamp-2 leading-relaxed">{{ $badge->description }}</p>

                            @if($isUnlocked)
                                <span class="mt-3 inline-block rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-semibold text-emerald-800">
                                    ✓ ปลดล็อกแล้ว
                                </span>
                            @else
                                <span class="mt-3 inline-block rounded-full bg-slate-200 px-2 py-0.5 text-[10px] font-medium text-slate-600">
                                    ยังไม่ได้รับ
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- 5. Timeline (10 เล่มล่าสุด) -->
            <section class="panel p-6 sm:p-8" x-data="journeyReveal()">
                <div class="flex items-center justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">ประวัติการอ่านที่ผ่านมา</h2>
                        <p class="text-sm text-slate-500">10 รายการล่าสุดที่คุณส่งคืนแล้ว</p>
                    </div>
                    <a href="{{ route('borrows.index') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-800 transition">
                        ดูประวัติการยืมทั้งหมด →
                    </a>
                </div>

                @if($timelineRecords->isEmpty())
                    <p class="py-8 text-center text-sm text-slate-500">ยังไม่มีประวัติการอ่าน</p>
                @else
                    <div class="relative border-l-2 border-emerald-100 ml-4 space-y-6">
                        @foreach($timelineRecords as $record)
                            @php
                                $isOnTime = $record->due_date === null || ($record->returned_at !== null && $record->returned_at->lte($record->due_date));
                            @endphp
                            <div class="relative pl-6 group">
                                <!-- Dot on line -->
                                <div class="absolute -left-[9px] top-4 h-4 w-4 rounded-full border-2 border-white {{ $isOnTime ? 'bg-emerald-500' : 'bg-rose-500' }} shadow-sm"></div>

                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-slate-100 bg-slate-50/60 p-4 transition hover:border-emerald-200 hover:bg-emerald-50/40">
                                    <div class="flex items-center gap-4">
                                        <!-- Cover Thumbnail -->
                                        <div class="h-14 w-11 shrink-0 overflow-hidden rounded-lg bg-slate-200 shadow-xs">
                                            @if($record->book?->coverImageUrl())
                                                <img src="{{ $record->book->coverImageUrl() }}" alt="{{ $record->book->title }}" class="h-full w-full object-cover">
                                            @else
                                                <div class="flex h-full items-center justify-center text-xl">📘</div>
                                            @endif
                                        </div>

                                        <div>
                                            <a href="{{ route('books.show', $record->book) }}" class="font-bold text-slate-900 hover:text-emerald-700 transition line-clamp-1">
                                                {{ $record->book?->title }}
                                            </a>
                                            <p class="text-xs text-slate-500 mt-0.5">
                                                โดย {{ $record->book?->author }} • <span class="text-emerald-700 font-medium">{{ $record->book?->category?->name ?? 'ทั่วไป' }}</span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex sm:flex-col items-center sm:items-end justify-between gap-1 text-xs">
                                        <span class="text-slate-500">คืนเมื่อ {{ $record->returned_at?->locale('th')->translatedFormat('j M Y') ?? '-' }}</span>
                                        <span class="status-badge {{ $isOnTime ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                            {{ $isOnTime ? '✓ คืนตรงเวลา (+15 แต้ม)' : '⚠ คืนเกินกำหนด (+7 แต้ม)' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <!-- Book Details Modal (using existing x-modal) -->
            <x-modal name="book-details-modal" maxWidth="md">
                <template x-if="selectedBook">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="h-28 w-20 shrink-0 overflow-hidden rounded-xl bg-slate-200 shadow-lg border border-slate-200">
                                <template x-if="selectedBook.cover_image">
                                    <img :src="selectedBook.cover_image.includes('://') ? selectedBook.cover_image : '/storage/' + selectedBook.cover_image" :alt="selectedBook.title" class="h-full w-full object-cover">
                                </template>
                                <template x-if="!selectedBook.cover_image">
                                    <div class="flex h-full items-center justify-center text-4xl">📘</div>
                                </template>
                            </div>

                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold text-slate-900 leading-tight line-clamp-2" x-text="selectedBook.title"></h3>
                                <p class="text-sm text-slate-500 mt-1" x-text="'โดย ' + selectedBook.author"></p>
                                <div class="mt-2 flex items-center gap-2">
                                    <span class="status-badge bg-emerald-100 text-emerald-800" x-text="selectedBook.category ? selectedBook.category.name : 'ทั่วไป'"></span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <p class="text-xs text-slate-600 line-clamp-4 leading-relaxed" x-text="selectedBook.description || 'ไม่มีคำอธิบายสำหรับหนังสือเล่มนี้'"></p>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-3">
                            <button type="button" @click="$dispatch('close-modal', 'book-details-modal')" class="button-secondary">
                                ปิด
                            </button>
                            <a :href="'/books/' + selectedBook.id" class="button-primary">
                                ไปยังหน้ารายละเอียดหนังสือ
                            </a>
                        </div>
                    </div>
                </template>
            </x-modal>
        </div>
    </div>
</x-app-layout>
