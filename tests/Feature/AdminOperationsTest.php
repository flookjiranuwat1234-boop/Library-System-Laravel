<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BorrowRecord;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\OverdueBookNotification;
use App\Notifications\TestEmailNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminOperationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_settings_and_queue_test_email(): void
    {
        Notification::fake();
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), ['low_stock_threshold' => 5])
            ->assertRedirect();

        $this->assertSame('5', Setting::query()->where('key', 'low_stock_threshold')->value('value'));

        $this->actingAs($admin)
            ->post(route('admin.settings.test-email'))
            ->assertRedirect();

        Notification::assertSentTo($admin, TestEmailNotification::class);
    }

    public function test_overdue_command_notifies_each_loan_only_once(): void
    {
        Notification::fake();
        $member = User::factory()->create(['role' => 'user']);
        $borrow = BorrowRecord::create([
            'user_id' => $member->id,
            'book_id' => Book::factory()->create()->id,
            'borrowed_at' => today()->subDays(20),
            'due_date' => today()->subDays(6),
            'status' => 'borrowed',
        ]);

        $this->artisan('library:notify-overdue')->assertSuccessful();
        $this->artisan('library:notify-overdue')->assertSuccessful();

        Notification::assertSentToTimes($member, OverdueBookNotification::class, 1);
        $this->assertSame('overdue', $borrow->fresh()->status);
        $this->assertNotNull($borrow->fresh()->overdue_notified_at);
    }

    public function test_new_borrow_request_is_stored_and_visible_in_admin_notifications(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['role' => 'user', 'name' => 'สมาชิกทดสอบ']);
        $book = Book::factory()->create(['title' => 'หนังสือรออนุมัติ', 'stock' => 2]);

        $this->actingAs($member)
            ->post(route('borrows.store'), ['book_id' => $book->id])
            ->assertRedirect(route('borrows.index'));

        $notification = $admin->notifications()->firstOrFail();
        $this->assertSame('borrow_request', $notification->data['type']);
        $this->assertSame('มีคำขอยืมหนังสือใหม่', $notification->data['title']);
        $this->assertSame(route('borrows.index'), $notification->data['url']);

        $this->actingAs($admin)
            ->get(route('admin.notifications.index'))
            ->assertSee('สมาชิกทดสอบ ขอยืมหนังสือ “หนังสือรออนุมัติ”')
            ->assertSee('เปิดรายการเพื่อดำเนินการ');

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertSee('การแจ้งเตือน (1 รายการใหม่)')
            ->assertSee(route('admin.notifications.index'))
            ->assertSee(route('admin.reports.index'))
            ->assertSee(route('admin.settings.edit'));
    }

    public function test_admin_can_download_excel_compatible_report_and_create_backup(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create(['role' => 'admin']);
        Book::factory()->create(['title' => 'รายงานหนังสือ']);

        $this->actingAs($admin)
            ->get(route('admin.reports.csv'))
            ->assertOk()
            ->assertDownload();

        $this->artisan('library:backup')->assertSuccessful();

        $this->assertCount(1, Storage::disk('local')->files('backups'));
    }

    public function test_member_cannot_access_admin_operations(): void
    {
        $member = User::factory()->create(['role' => 'user']);

        $this->actingAs($member)->get(route('admin.settings.edit'))->assertForbidden();
        $this->actingAs($member)->get(route('admin.reports.index'))->assertForbidden();
        $this->actingAs($member)->get(route('admin.notifications.index'))->assertForbidden();
    }
}
