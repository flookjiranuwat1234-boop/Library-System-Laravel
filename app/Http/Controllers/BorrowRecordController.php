<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowRecord;
use App\Models\User;
use App\Notifications\NewBorrowRequestNotification;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class BorrowRecordController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            $borrows = BorrowRecord::with(['book', 'user'])->latest()->paginate(20);
        } else {
            $borrows = BorrowRecord::with('book')->whereBelongsTo($user)->latest()->paginate(20);
        }

        return view('borrows.index', compact('borrows'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'book_id' => ['required', 'integer', 'exists:books,id'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'quick_name' => ['nullable', 'string', 'max:255'],
            'quick_email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
        ]);

        if (Auth::user()->role === 'admin' && ! empty($validated['quick_name'])) {
            $email = ! empty($validated['quick_email'])
                ? $validated['quick_email']
                : 'user_'.time().'@library.local';

            $newUser = User::create([
                'name' => $validated['quick_name'],
                'email' => $email,
                'password' => bcrypt('password123'),
                'role' => 'user',
            ]);

            $validated['user_id'] = $newUser->id;
        }

        $borrowerId = (Auth::user()->role === 'admin' && ! empty($validated['user_id']))
            ? (int) $validated['user_id']
            : Auth::id();

        $isAdminIssue = Auth::user()->role === 'admin' && ! empty($validated['user_id']);

        $result = DB::transaction(function () use ($validated, $borrowerId, $isAdminIssue): BorrowRecord|string {
            $book = Book::query()->lockForUpdate()->findOrFail($validated['book_id']);

            if ($book->stock <= 0) {
                return 'out-of-stock';
            }

            $alreadyBorrowed = BorrowRecord::query()
                ->where('user_id', $borrowerId)
                ->where('book_id', $book->id)
                ->whereIn('status', ['pending', 'borrowed', 'overdue'])
                ->exists();

            if ($alreadyBorrowed) {
                return 'already-borrowed';
            }

            if ($isAdminIssue) {
                $book->decrement('stock');

                return BorrowRecord::create([
                    'user_id' => $borrowerId,
                    'book_id' => $book->id,
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now(),
                    'borrowed_at' => today(),
                    'due_date' => today()->addDays(14),
                    'status' => 'borrowed',
                ]);
            }

            return BorrowRecord::create([
                'user_id' => $borrowerId,
                'book_id' => $book->id,
                'status' => 'pending',
            ]);
        });

        if ($result === 'out-of-stock') {
            return back()->with('error', 'หนังสือเล่มนี้ไม่มีจำนวนพร้อมให้ยืม');
        }

        if ($result === 'already-borrowed') {
            return back()->with('error', 'สมาชิกมีคำขอหรือกำลังยืมหนังสือเล่มนี้อยู่แล้ว');
        }

        $result->load(['book', 'user']);
        if (! $isAdminIssue) {
            $administrators = User::query()->where('role', 'admin')->get();
            Notification::send($administrators, new NewBorrowRequestNotification($result));
        }

        app(ActivityLogService::class)->log('borrow.requested', "ยืมหนังสือ: {$result->book->title} (สำหรับ {$result->user->name})", $result);

        return back()->with('success', $isAdminIssue ? "บันทึกการยืมหนังสือให้คุณ {$result->user->name} เรียบร้อยแล้ว" : 'ส่งคำขอยืมแล้ว กรุณารอผู้ดูแลอนุมัติ');
    }

    public function approve(BorrowRecord $borrow): RedirectResponse
    {
        $result = DB::transaction(function () use ($borrow): string {
            $borrow = BorrowRecord::query()->lockForUpdate()->findOrFail($borrow->id);

            if ($borrow->status !== 'pending') {
                return 'not-pending';
            }

            $book = Book::query()->lockForUpdate()->findOrFail($borrow->book_id);

            if ($book->stock <= 0) {
                return 'out-of-stock';
            }

            $book->decrement('stock');
            $borrow->update([
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'borrowed_at' => today(),
                'due_date' => today()->addDays(14),
                'status' => 'borrowed',
            ]);

            return 'approved';
        });

        if ($result === 'out-of-stock') {
            return back()->with('error', 'ไม่สามารถอนุมัติได้ เนื่องจากหนังสือไม่มีจำนวนคงเหลือ');
        }

        if ($result === 'not-pending') {
            return back()->with('error', 'คำขอนี้ได้รับการดำเนินการแล้ว');
        }

        $borrow->load('book');
        app(ActivityLogService::class)->log('borrow.approved', "อนุมัติการยืม: {$borrow->book->title}", $borrow);

        return back()->with('success', 'อนุมัติคำขอยืมเรียบร้อยแล้ว');
    }

    public function reject(BorrowRecord $borrow): RedirectResponse
    {
        $rejected = BorrowRecord::query()
            ->whereKey($borrow->id)
            ->where('status', 'pending')
            ->update([
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'status' => 'rejected',
            ]);

        if ($rejected === 0) {
            return back()->with('error', 'คำขอนี้ได้รับการดำเนินการแล้ว');
        }

        $borrow->load('book');
        app(ActivityLogService::class)->log('borrow.rejected', "ปฏิเสธการยืม: {$borrow->book->title}", $borrow);

        return back()->with('success', 'ปฏิเสธคำขอยืมเรียบร้อยแล้ว');
    }

    public function returnBook(BorrowRecord $borrow): RedirectResponse
    {
        $returned = DB::transaction(function () use ($borrow): bool {
            $borrow = BorrowRecord::query()->lockForUpdate()->findOrFail($borrow->id);

            if (! in_array($borrow->status, ['borrowed', 'overdue'], true)) {
                return false;
            }

            $borrow->update([
                'returned_at' => today(),
                'status' => 'returned',
            ]);

            Book::query()->whereKey($borrow->book_id)->increment('stock');

            return true;
        });

        if (! $returned) {
            return back()->with('error', 'หนังสือรายการนี้ถูกคืนแล้ว');
        }

        $borrow->load('book');
        app(ActivityLogService::class)->log('borrow.returned', "บันทึกการคืน: {$borrow->book->title}", $borrow);

        return back()->with('success', 'บันทึกการคืนหนังสือเรียบร้อยแล้ว');
    }

    public function renew(BorrowRecord $borrow): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        // ผู้ใช้ทั่วไปต่ออายุได้เฉพาะรายการของตัวเอง
        if ($user->role !== 'admin' && $borrow->user_id !== $user->id) {
            abort(403);
        }

        if (! $borrow->canRenew()) {
            return back()->with('error', 'ไม่สามารถต่ออายุได้ (สถานะไม่ถูกต้องหรือต่ออายุครบ 2 ครั้งแล้ว)');
        }

        $borrow->update([
            'due_date' => $borrow->due_date->addDays(7),
            'renew_count' => $borrow->renew_count + 1,
            'status' => 'borrowed',
        ]);

        $borrow->load('book');
        app(ActivityLogService::class)->log('borrow.renewed', "ต่ออายุการยืม: {$borrow->book->title} (ครั้งที่ {$borrow->renew_count})", $borrow);

        return back()->with('success', "ต่ออายุการยืมเรียบร้อยแล้ว กำหนดคืนใหม่: {$borrow->due_date->locale('th')->translatedFormat('j M Y')}");
    }
}
