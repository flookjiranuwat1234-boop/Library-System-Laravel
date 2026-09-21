<?php

namespace Tests\Feature;

use App\Models\Badge;
use App\Models\Book;
use App\Models\BorrowRecord;
use App\Models\Category;
use App\Models\ReadingStat;
use App\Models\User;
use Database\Seeders\BadgeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ReadingJourneyTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_user_sees_journey_page_with_empty_state_and_initial_stats(): void
    {
        $this->seed(BadgeSeeder::class);
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('journey.index'));

        $response->assertOk();
        $response->assertSee('เส้นทางการอ่านของฉัน');
        $response->assertSee('ชั้นหนังสือของคุณยังว่างอยู่');
        $response->assertSee('0');

        $this->assertDatabaseHas('reading_stats', [
            'user_id' => $user->id,
            'total_books_read' => 0,
            'total_points' => 0,
        ]);
    }

    public function test_returning_book_on_time_calculates_points_and_unlocks_badge(): void
    {
        $this->seed(BadgeSeeder::class);
        $user = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create(['name' => 'Fiction']);
        $book = Book::factory()->create(['category_id' => $category->id, 'stock' => 1]);

        $borrow = BorrowRecord::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_at' => today()->subDays(5),
            'due_date' => today()->addDays(5),
            'status' => 'borrowed',
        ]);

        $this->actingAs($admin)->post(route('borrows.return', $borrow))
            ->assertSessionHas('success', 'บันทึกการคืนหนังสือเรียบร้อยแล้ว');

        // 10 (return) + 5 (on-time bonus) + 15 (distinct category) = 30
        $stat = ReadingStat::where('user_id', $user->id)->first();
        $this->assertNotNull($stat);
        $this->assertSame(1, $stat->total_books_read);
        $this->assertSame(1, $stat->on_time_returns);
        $this->assertSame(0, $stat->late_returns);
        $this->assertSame(1, $stat->categories_explored);
        $this->assertSame(30, $stat->total_points);

        // Assert first_book badge unlocked
        $badge = Badge::where('code', 'first_book')->first();
        $this->assertDatabaseHas('user_badges', [
            'user_id' => $user->id,
            'badge_id' => $badge->id,
        ]);
    }

    public function test_returning_book_late_applies_penalty(): void
    {
        $this->seed(BadgeSeeder::class);
        $user = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create(['name' => 'Sci-Fi']);
        $book = Book::factory()->create(['category_id' => $category->id, 'stock' => 1]);

        $borrow = BorrowRecord::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_at' => today()->subDays(20),
            'due_date' => today()->subDays(5),
            'status' => 'borrowed',
        ]);

        $this->actingAs($admin)->post(route('borrows.return', $borrow))
            ->assertSessionHas('success', 'บันทึกการคืนหนังสือเรียบร้อยแล้ว');

        // 10 (return) - 3 (late penalty) + 15 (distinct category) = 22
        $stat = ReadingStat::where('user_id', $user->id)->first();
        $this->assertNotNull($stat);
        $this->assertSame(1, $stat->total_books_read);
        $this->assertSame(0, $stat->on_time_returns);
        $this->assertSame(1, $stat->late_returns);
        $this->assertSame(22, $stat->total_points);
    }

    public function test_returning_book_with_null_due_date_is_treated_as_on_time(): void
    {
        $this->seed(BadgeSeeder::class);
        $user = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);
        $book = Book::factory()->create(['stock' => 1]);

        $borrow = BorrowRecord::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_at' => today()->subDays(5),
            'due_date' => null,
            'status' => 'borrowed',
        ]);

        $this->actingAs($admin)->post(route('borrows.return', $borrow))
            ->assertSessionHas('success', 'บันทึกการคืนหนังสือเรียบร้อยแล้ว');

        $stat = ReadingStat::where('user_id', $user->id)->first();
        $this->assertNotNull($stat);
        $this->assertSame(1, $stat->on_time_returns);
        $this->assertSame(0, $stat->late_returns);
    }

    public function test_recalculate_command_is_idempotent(): void
    {
        $this->seed(BadgeSeeder::class);
        $user = User::factory()->create(['role' => 'user']);
        $category = Category::factory()->create();
        $book = Book::factory()->create(['category_id' => $category->id]);

        BorrowRecord::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_at' => today()->subDays(5),
            'due_date' => today()->addDays(5),
            'returned_at' => today(),
            'status' => 'returned',
        ]);

        // Run recalculate 1st time
        $this->artisan('library:recalculate-journey')->assertSuccessful();

        $stat1 = ReadingStat::where('user_id', $user->id)->first();
        $badgeCount1 = DB::table('user_badges')->where('user_id', $user->id)->count();

        // Run recalculate 2nd time
        $this->artisan('library:recalculate-journey')->assertSuccessful();

        $stat2 = ReadingStat::where('user_id', $user->id)->first();
        $badgeCount2 = DB::table('user_badges')->where('user_id', $user->id)->count();

        $this->assertSame($stat1->total_points, $stat2->total_points);
        $this->assertSame($stat1->total_books_read, $stat2->total_books_read);
        $this->assertSame($badgeCount1, $badgeCount2);
    }

    public function test_admin_is_redirected_to_admin_dashboard_when_accessing_journey(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('journey.index'));

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_journey_page_database_query_count_is_optimized(): void
    {
        $this->seed(BadgeSeeder::class);
        $user = User::factory()->create(['role' => 'user']);
        $categories = Category::factory()->count(3)->create();

        for ($i = 0; $i < 5; $i++) {
            $book = Book::factory()->create(['category_id' => $categories[$i % 3]->id]);
            BorrowRecord::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'borrowed_at' => today()->subDays(10 - $i),
                'due_date' => today()->addDays(4),
                'returned_at' => today()->subDays(5 - $i),
                'status' => 'returned',
            ]);
        }

        // Prime any initial state
        $this->actingAs($user)->get(route('journey.index'));

        $queryCount = 0;
        DB::listen(function () use (&$queryCount): void {
            $queryCount++;
        });

        $response = $this->actingAs($user)->get(route('journey.index'));
        $response->assertOk();

        // Assert query count is <= 6 queries
        $this->assertLessThanOrEqual(6, $queryCount);
    }
}
