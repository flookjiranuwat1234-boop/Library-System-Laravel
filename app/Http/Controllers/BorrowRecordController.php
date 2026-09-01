<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BorrowRecordController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            $borrows = BorrowRecord::with(['book', 'user'])->orderByDesc('borrowed_at')->paginate(20);
        } else {
            $borrows = BorrowRecord::with('book')->whereBelongsTo($user)->orderByDesc('borrowed_at')->paginate(20);
        }

        return view('borrows.index', compact('borrows'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'book_id' => ['required', 'integer', 'exists:books,id'],
        ]);

        $result = DB::transaction(function () use ($validated): string {
            $book = Book::query()->lockForUpdate()->findOrFail($validated['book_id']);

            if ($book->stock <= 0) {
                return 'out-of-stock';
            }

            $alreadyBorrowed = BorrowRecord::query()
                ->where('user_id', Auth::id())
                ->where('book_id', $book->id)
                ->whereIn('status', ['borrowed', 'overdue'])
                ->exists();

            if ($alreadyBorrowed) {
                return 'already-borrowed';
            }

            $book->decrement('stock');

            BorrowRecord::create([
                'user_id' => Auth::id(),
                'book_id' => $book->id,
                'borrowed_at' => today(),
                'due_date' => today()->addDays(14),
                'status' => 'borrowed',
            ]);

            return 'borrowed';
        });

        if ($result === 'out-of-stock') {
            return back()->with('error', 'หนังสือเล่มนี้ถูกยืมหมดแล้ว');
        }

        if ($result === 'already-borrowed') {
            return back()->with('error', 'คุณกำลังยืมหนังสือเล่มนี้อยู่แล้ว');
        }

        return redirect()->route('borrows.index')->with('success', 'ยืมหนังสือเรียบร้อยแล้ว');
    }

    public function returnBook(BorrowRecord $borrow): RedirectResponse
    {
        $returned = DB::transaction(function () use ($borrow): bool {
            $borrow = BorrowRecord::query()->lockForUpdate()->findOrFail($borrow->id);

            if ($borrow->status === 'returned') {
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

        return back()->with('success', 'บันทึกการคืนหนังสือเรียบร้อยแล้ว');
    }
}
