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
        return response()->streamDownload(function (): void {
            $stream = fopen('php://output', 'wb');
            fwrite($stream, "\xEF\xBB\xBF");
            fputcsv($stream, ['หนังสือ', 'สมาชิก', 'วันที่ยืม', 'กำหนดคืน', 'สถานะ']);

            BorrowRecord::with(['book', 'user'])->latest('borrowed_at')->lazy()->each(
                fn (BorrowRecord $record) => fputcsv($stream, [
                    $record->book->title,
                    $record->user->name,
                    $record->borrowed_at->format('Y-m-d'),
                    $record->due_date->format('Y-m-d'),
                    $record->status,
                ]),
            );
            fclose($stream);
        }, 'library-report-'.today()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
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
            'popularBooks' => Book::query()->withCount('borrowRecords')->orderByDesc('borrow_records_count')->take(10)->get(),
            'recentRecords' => BorrowRecord::with(['book', 'user'])->latest('borrowed_at')->take(20)->get(),
        ];
    }
}
