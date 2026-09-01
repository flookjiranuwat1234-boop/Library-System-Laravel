<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookCoverManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_the_create_book_page_without_route_binding_conflict(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/books/create')
            ->assertOk()
            ->assertSee('เพิ่มหนังสือ');
    }

    public function test_admin_can_upload_a_book_cover(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        $this->actingAs($admin)
            ->post(route('books.store'), [
                'title' => 'หนังสือพร้อมปก',
                'author' => 'นักเขียนทดสอบ',
                'category_id' => $category->id,
                'stock' => 4,
                'cover_image' => UploadedFile::fake()->image('cover.jpg', 600, 900),
            ])
            ->assertRedirect(route('books.index'));

        $book = Book::query()->where('title', 'หนังสือพร้อมปก')->firstOrFail();

        Storage::disk('public')->assertExists($book->cover_image);
    }

    public function test_replacing_a_cover_deletes_the_previous_file(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('book-covers/old.jpg', 'old cover');
        $admin = User::factory()->create(['role' => 'admin']);
        $book = Book::factory()->create(['cover_image' => 'book-covers/old.jpg']);

        $this->actingAs($admin)
            ->put(route('books.update', $book), [
                'title' => $book->title,
                'author' => $book->author,
                'category_id' => $book->category_id,
                'stock' => $book->stock,
                'cover_image' => UploadedFile::fake()->image('new-cover.webp', 600, 900),
            ])
            ->assertRedirect(route('books.index'));

        $newCoverImage = $book->fresh()->cover_image;

        Storage::disk('public')->assertMissing('book-covers/old.jpg');
        Storage::disk('public')->assertExists($newCoverImage);
    }

    public function test_deleting_a_book_deletes_its_stored_cover(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('book-covers/deleted.jpg', 'cover');
        $admin = User::factory()->create(['role' => 'admin']);
        $book = Book::factory()->create(['cover_image' => 'book-covers/deleted.jpg']);

        $this->actingAs($admin)
            ->delete(route('books.destroy', $book))
            ->assertRedirect(route('books.index'));

        $this->assertModelMissing($book);
        Storage::disk('public')->assertMissing('book-covers/deleted.jpg');
    }

    public function test_book_cover_rejects_non_image_files(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('books.create'))
            ->post(route('books.store'), [
                'title' => 'ไฟล์ไม่ถูกต้อง',
                'author' => 'นักเขียนทดสอบ',
                'category_id' => Category::factory()->create()->id,
                'stock' => 1,
                'cover_image' => UploadedFile::fake()->create('malware.php', 10, 'application/x-php'),
            ])
            ->assertRedirect(route('books.create'))
            ->assertSessionHasErrors('cover_image');

        $this->assertDatabaseMissing('books', ['title' => 'ไฟล์ไม่ถูกต้อง']);
    }
}
