<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BorrowRecord;
use App\Models\Category;
use App\Models\User;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('admin.reports', $this->reportData());
    }

    public function print(): View
    {
        return view('admin.reports-print', $this->reportData());
    }

    public function csv(): StreamedResponse
    {
        $totalBooks = Book::count();
        $totalMembers = User::where('role', 'user')->count();
        $activeLoans = BorrowRecord::whereIn('status', ['borrowed', 'overdue'])->count();
        $overdueLoans = BorrowRecord::whereIn('status', ['borrowed', 'overdue'])->whereDate('due_date', '<', today())->count();

        $statusTranslations = [
            'borrowed' => 'กำลังยืม',
            'returned' => 'คืนเรียบร้อย',
            'overdue' => 'เกินกำหนดส่ง',
            'pending' => 'รออนุมัติ',
            'rejected' => 'ไม่อนุมัติ',
        ];

        return response()->streamDownload(function () use ($totalBooks, $totalMembers, $activeLoans, $overdueLoans, $statusTranslations): void {
            $stream = fopen('php://output', 'wb');
            // UTF-8 BOM for Thai language support in Microsoft Excel
            fwrite($stream, "\xEF\xBB\xBF");

            // Meta summary header rows
            fputcsv($stream, ['รายงานสรุปการยืม-คืนหนังสือ ระบบจัดคลังห้องสมุด (Library Circulation Detailed Report)']);
            fputcsv($stream, ['วันที่ออกรายงาน:', now()->locale('th')->translatedFormat('d F Y H:i:s')]);
            fputcsv($stream, ['ผู้ออกรายงาน:', auth()->user()?->name ?? 'ผู้ดูแลระบบ']);
            fputcsv($stream, ['สรุปภาพรวม:', "หนังสือทั้งหมด {$totalBooks} เล่ม | สมาชิก {$totalMembers} คน | กำลังยืม {$activeLoans} รายการ | เกินกำหนด {$overdueLoans} รายการ"]);
            fputcsv($stream, []);

            // Column Headers
            fputcsv($stream, [
                'ลำดับ',
                'รหัสรายการ',
                'ชื่อหนังสือ',
                'หมวดหมู่',
                'ชื่อผู้ยืม',
                'อีเมลสมาชิก',
                'วันที่ยืม',
                'กำหนดวันส่งคืน',
                'วันที่คืนจริง',
                'สถานะรายการ',
                'จำนวนครั้งการต่ออายุ',
            ]);

            $index = 1;
            BorrowRecord::with(['book.category', 'user'])->latest('borrowed_at')->lazy()->each(
                function (BorrowRecord $record) use ($stream, &$index, $statusTranslations): void {
                    $statusText = $statusTranslations[$record->status] ?? $record->status;
                    if ($record->status === 'borrowed' && $record->due_date && $record->due_date->isPast()) {
                        $statusText = 'เกินกำหนดส่ง';
                    }

                    fputcsv($stream, [
                        $index++,
                        'BR-'.str_pad((string) $record->id, 5, '0', STR_PAD_LEFT),
                        $record->book?->title ?? 'ไม่พบหนังสือ',
                        $record->book?->category?->name ?? 'ทั่วไป',
                        $record->user?->name ?? 'ไม่พบผู้ใช้',
                        $record->user?->email ?? '-',
                        $record->borrowed_at?->format('d/m/Y') ?? '-',
                        $record->due_date?->format('d/m/Y') ?? '-',
                        $record->returned_at?->format('d/m/Y') ?? '-',
                        $statusText,
                        $record->renew_count ?? 0,
                    ]);
                }
            );

            fclose($stream);
        }, 'library-borrowing-report-'.today()->format('Y-m-d').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /** @return array<string, mixed> */
    private function reportData(): array
    {
        return [
            'totalBooks' => Book::count(),
            'totalMembers' => User::where('role', 'user')->count(),
            'totalCategories' => Category::count(),
            'activeLoans' => BorrowRecord::whereIn('status', ['borrowed', 'overdue'])->count(),
            'overdueLoans' => BorrowRecord::whereIn('status', ['borrowed', 'overdue'])->whereDate('due_date', '<', today())->count(),
            'popularBooks' => Book::query()->with('category')->withCount('borrowRecords')->orderByDesc('borrow_records_count')->take(10)->get(),
            'recentRecords' => BorrowRecord::with(['book.category', 'user'])->latest('borrowed_at')->take(50)->get(),
        ];
    }
}
