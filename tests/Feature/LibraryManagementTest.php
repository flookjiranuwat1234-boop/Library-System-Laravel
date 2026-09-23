<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BorrowRecord;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LibraryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_shows_library_branding(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('ระบบจัดการห้องสมุด')
            ->assertSee('เข้าสู่ระบบ')
            ->assertSee('สมัครสมาชิก');
    }

    public function test_member_can_view_book_catalog_and_open_book_details(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $category = Category::factory()->create();
        $book = Book::factory()->create([
            'category_id' => $category->id,
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
            'stock' => 3,
        ]);

        $this->actingAs($user)
            ->get('/books')
            ->assertOk()
            ->assertSee('Clean Code');

        $this->actingAs($user)
            ->get('/books/'.$book->id)
            ->assertOk()
            ->assertSee('ส่งคำขอยืมหนังสือ');
    }

    public function test_member_dashboard_shows_new_books_and_upcoming_due_dates(): void
    {
        $member = User::factory()->create(['role' => 'user']);
        $book = Book::factory()->create(['title' => 'หนังสือมาใหม่สำหรับสมาชิก']);
        BorrowRecord::create([
            'user_id' => $member->id,
            'book_id' => $book->id,
            'borrowed_at' => today(),
            'due_date' => today()->addDays(7),
            'status' => 'borrowed',
        ]);

        $this->actingAs($member)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('หนังสือมาใหม่สำหรับสมาชิก')
            ->assertSee('กำหนดคืน');
    }

    public function test_book_details_show_that_member_already_has_an_active_loan(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $book = Book::factory()->create(['stock' => 2]);
        BorrowRecord::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_at' => today()->subDay(),
            'due_date' => today()->addDays(13),
            'status' => 'borrowed',
        ]);

        $this->actingAs($user)
            ->get(route('books.show', $book))
            ->assertSee('คุณกำลังยืมหนังสือเล่มนี้อยู่')
            ->assertDontSee('name="book_id"', false);
    }

    public function test_category_details_endpoint_rejects_get_requests(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        $this->actingAs($admin)
            ->get('/categories/'.$category->id)
            ->assertMethodNotAllowed();
    }

    public function test_user_cannot_borrow_same_book_twice_when_already_borrowed(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $category = Category::factory()->create();
        $book = Book::factory()->create([
            'category_id' => $category->id,
            'stock' => 2,
        ]);

        BorrowRecord::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_at' => now()->subDay(),
            'due_date' => now()->addDays(13),
            'status' => 'borrowed',
        ]);

        $this->actingAs($user)
            ->from('/books/'.$book->id)
            ->post('/borrows', ['book_id' => $book->id])
            ->assertSessionHas('error', 'สมาชิกมีคำขอหรือกำลังยืมหนังสือเล่มนี้อยู่แล้ว')
            ->assertRedirect('/books/'.$book->id);
    }

    public function test_member_can_request_available_book_without_decreasing_stock(): void
    {
        $this->travelTo('2026-08-31 10:00:00');
        $user = User::factory()->create(['role' => 'user']);
        $book = Book::factory()->create(['stock' => 2]);

        $this->actingAs($user)
            ->from(route('borrows.index'))
            ->post(route('borrows.store'), ['book_id' => $book->id])
            ->assertRedirect(route('borrows.index'))
            ->assertSessionHas('success', 'ส่งคำขอยืมแล้ว กรุณารอผู้ดูแลอนุมัติ');

        $this->assertDatabaseHas('borrow_records', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_at' => null,
            'due_date' => null,
            'status' => 'pending',
        ]);
        $this->assertSame(2, $book->fresh()->stock);
    }

    public function test_out_of_stock_book_cannot_be_borrowed(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $book = Book::factory()->create(['stock' => 0]);

        $this->actingAs($user)
            ->from(route('books.show', $book))
            ->post(route('borrows.store'), ['book_id' => $book->id])
            ->assertRedirect(route('books.show', $book))
            ->assertSessionHas('error', 'หนังสือเล่มนี้ไม่มีจำนวนพร้อมให้ยืม');

        $this->assertDatabaseMissing('borrow_records', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);
        $this->assertSame(0, $book->fresh()->stock);
    }

    public function test_admin_can_return_overdue_book_only_once(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $book = Book::factory()->create(['stock' => 0]);
        $borrow = BorrowRecord::create([
            'user_id' => User::factory()->create()->id,
            'book_id' => $book->id,
            'borrowed_at' => now()->subDays(20),
            'due_date' => now()->subDays(6),
            'status' => 'overdue',
        ]);

        $this->actingAs($admin)
            ->from(route('borrows.index'))
            ->post(route('borrows.return', $borrow))
            ->assertRedirect(route('borrows.index'))
            ->assertSessionHas('success', 'บันทึกการคืนหนังสือเรียบร้อยแล้ว');

        $this->assertDatabaseHas('borrow_records', [
            'id' => $borrow->id,
            'status' => 'returned',
        ]);
        $this->assertSame(1, $book->fresh()->stock);

        $this->actingAs($admin)
            ->from(route('borrows.index'))
            ->post(route('borrows.return', $borrow))
            ->assertSessionHas('error', 'หนังสือรายการนี้ถูกคืนแล้ว');

        $this->assertSame(1, $book->fresh()->stock);
    }

    public function test_member_catalog_hides_inventory_management_actions(): void
    {
        $member = User::factory()->create(['role' => 'user']);
        Book::factory()->create(['title' => 'Visible Book']);

        $this->actingAs($member)
            ->get(route('books.index'))
            ->assertSee('Visible Book')
            ->assertDontSee('เพิ่มหนังสือ')
            ->assertDontSee('แก้ไข')
            ->assertDontSee('ลบ');
    }

    public function test_book_validation_errors_are_shown_in_thai(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('books.create'))
            ->post(route('books.store'), [])
            ->assertRedirect(route('books.create'))
            ->assertSessionHasErrors([
                'title' => 'กรุณากรอก ชื่อหนังสือ',
                'author' => 'กรุณากรอก ชื่อผู้แต่ง',
                'category_id' => 'กรุณากรอก หมวดหมู่',
                'stock' => 'กรุณากรอก จำนวนคงเหลือ',
            ]);
    }

    public function test_book_with_borrowing_history_cannot_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $book = Book::factory()->create();
        BorrowRecord::create([
            'user_id' => User::factory()->create()->id,
            'book_id' => $book->id,
            'borrowed_at' => now()->subDays(15),
            'due_date' => now()->subDay(),
            'status' => 'returned',
            'returned_at' => now()->subDays(2),
        ]);

        $this->actingAs($admin)
            ->from(route('books.index'))
            ->delete(route('books.destroy', $book))
            ->assertRedirect(route('books.index'))
            ->assertSessionHas('error', 'ไม่สามารถลบหนังสือที่มีประวัติการยืมได้');

        $this->assertModelExists($book);
    }

    public function test_database_seeder_is_idempotent_when_run_twice(): void
    {
        $this->artisan('db:seed')->assertSuccessful();
        $this->artisan('db:seed')->assertSuccessful();

        $this->assertDatabaseHas('users', ['email' => 'admin@example.com']);
        $this->assertDatabaseHas('users', ['email' => 'user@example.com']);
        $this->assertDatabaseCount('users', 7);
        $this->assertDatabaseCount('categories', 12);
        $this->assertDatabaseCount('books', 50);
        $this->assertDatabaseHas('books', [
            'title' => 'ความสุขของกะทิ',
            'author' => 'งามพรรณ เวชชาชีวะ',
        ]);
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Category::factory()->count(3)->create();
        Book::factory()->count(5)->create();

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('แดชบอร์ดผู้ดูแล')
            ->assertSee('หนังสือทั้งหมด')
            ->assertSee('กำลังยืม')
            ->assertSee('เกินกำหนด');
    }

    public function test_member_cannot_access_admin_dashboard(): void
    {
        $member = User::factory()->create(['role' => 'user']);

        $this->actingAs($member)
            ->get('/admin/dashboard')
            ->assertForbidden();
    }

    public function test_unauthenticated_user_cannot_access_admin_dashboard(): void
    {
        $this->get('/admin/dashboard')
            ->assertRedirect(route('login'));
    }
}
