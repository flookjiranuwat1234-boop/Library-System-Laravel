<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BorrowRecord;
use App\Models\User;
use App\Notifications\NewBorrowRequestNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class BorrowApprovalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_request_waits_for_approval_without_decreasing_stock(): void
    {
        $member = User::factory()->create(['role' => 'user']);
        $administrators = User::factory()->count(2)->create(['role' => 'admin']);
        $book = Book::factory()->create(['stock' => 2]);
        Notification::fake();

        $this->actingAs($member)->from(route('borrows.index'))->post(route('borrows.store'), ['book_id' => $book->id])
            ->assertRedirect(route('borrows.index'))
            ->assertSessionHas('success', 'ส่งคำขอยืมแล้ว กรุณารอผู้ดูแลอนุมัติ');

        $this->assertDatabaseHas('borrow_records', ['user_id' => $member->id, 'book_id' => $book->id, 'status' => 'pending', 'borrowed_at' => null, 'due_date' => null]);
        $this->assertSame(2, $book->fresh()->stock);
        Notification::assertSentTo(
            $administrators,
            NewBorrowRequestNotification::class,
            fn (NewBorrowRequestNotification $notification): bool => $notification->borrowRecord->book_id === $book->id
                && $notification->borrowRecord->user_id === $member->id,
        );
    }

    public function test_admin_approval_starts_loan_and_decreases_stock(): void
    {
        $this->travelTo('2026-09-01 10:00:00');
        $admin = User::factory()->create(['role' => 'admin']);
        $book = Book::factory()->create(['stock' => 2]);
        $borrow = BorrowRecord::create(['user_id' => User::factory()->create()->id, 'book_id' => $book->id, 'status' => 'pending']);

        $this->actingAs($admin)->post(route('borrows.approve', $borrow))
            ->assertSessionHas('success', 'อนุมัติคำขอยืมเรียบร้อยแล้ว');

        $this->assertDatabaseHas('borrow_records', ['id' => $borrow->id, 'reviewed_by' => $admin->id, 'status' => 'borrowed', 'borrowed_at' => '2026-09-01 00:00:00', 'due_date' => '2026-09-15 00:00:00']);
        $this->assertSame(1, $book->fresh()->stock);
    }

    public function test_admin_can_reject_request_without_decreasing_stock(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $book = Book::factory()->create(['stock' => 1]);
        $borrow = BorrowRecord::create(['user_id' => User::factory()->create()->id, 'book_id' => $book->id, 'status' => 'pending']);

        $this->actingAs($admin)->post(route('borrows.reject', $borrow))
            ->assertSessionHas('success', 'ปฏิเสธคำขอยืมเรียบร้อยแล้ว');

        $this->assertDatabaseHas('borrow_records', ['id' => $borrow->id, 'reviewed_by' => $admin->id, 'status' => 'rejected']);
        $this->assertSame(1, $book->fresh()->stock);
    }

    public function test_member_cannot_approve_or_reject_requests(): void
    {
        $member = User::factory()->create(['role' => 'user']);
        $borrow = BorrowRecord::create(['user_id' => $member->id, 'book_id' => Book::factory()->create()->id, 'status' => 'pending']);

        $this->actingAs($member)->post(route('borrows.approve', $borrow))->assertForbidden();
        $this->actingAs($member)->post(route('borrows.reject', $borrow))->assertForbidden();

        $this->assertSame('pending', $borrow->fresh()->status);
    }

    public function test_admin_cannot_approve_pending_request_when_stock_is_empty(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $book = Book::factory()->create(['stock' => 0]);
        $borrow = BorrowRecord::create(['user_id' => User::factory()->create()->id, 'book_id' => $book->id, 'status' => 'pending']);

        $this->actingAs($admin)->post(route('borrows.approve', $borrow))
            ->assertSessionHas('error', 'ไม่สามารถอนุมัติได้ เนื่องจากหนังสือไม่มีจำนวนคงเหลือ');

        $this->assertSame('pending', $borrow->fresh()->status);
        $this->assertSame(0, $book->fresh()->stock);
    }
}
