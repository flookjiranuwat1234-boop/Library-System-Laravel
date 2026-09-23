<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BorrowRecord;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $member = User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        $extraUsers = [
            ['name' => 'สมชาย ใจดี (Senior Dev)', 'email' => 'somchai@example.com'],
            ['name' => 'อนันต์ สุขเสริฐ (System Architect)', 'email' => 'anan@example.com'],
            ['name' => 'นภา วงศ์ไพศาล (Data Scientist)', 'email' => 'napha@example.com'],
            ['name' => 'กิตติพงษ์ วรโชติ (DevOps Specialist)', 'email' => 'kittipong@example.com'],
            ['name' => 'บรรณารักษ์ อาวุโส (Senior Librarian)', 'email' => 'librarian@example.com'],
        ];

        $users = [];
        foreach ($extraUsers as $u) {
            $users[$u['email']] = User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make('password'),
                    'role' => 'user', // All member accounts assigned role user
                ]
            );
        }

        $categories = [
            'Fiction',
            'Non-Fiction',
            'Science',
            'Technology',
        ];

        $categoriesCollection = [];
        foreach ($categories as $categoryName) {
            $categoriesCollection[] = Category::firstOrCreate(['name' => $categoryName]);
        }

        $bookData = [
            ['title' => 'Clean Code', 'author' => 'Robert C. Martin', 'category_id' => $categoriesCollection[3]->id, 'stock' => 5],
            ['title' => 'The Pragmatic Programmer', 'author' => 'Andrew Hunt', 'category_id' => $categoriesCollection[3]->id, 'stock' => 3],
            ['title' => 'A Brief History of Time', 'author' => 'Stephen Hawking', 'category_id' => $categoriesCollection[2]->id, 'stock' => 2],
            ['title' => 'Pride and Prejudice', 'author' => 'Jane Austen', 'category_id' => $categoriesCollection[0]->id, 'stock' => 4],
        ];

        foreach ($bookData as $book) {
            Book::firstOrCreate(
                ['title' => $book['title']],
                [
                    'author' => $book['author'],
                    'category_id' => $book['category_id'],
                    'cover_image' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=300&q=80',
                    'description' => 'Sample library book for the catalog.',
                    'stock' => $book['stock'],
                ]
            );
        }

        $this->call(ThaiBookSeeder::class);
        $books = Book::all();

        if ($books->count() >= 5) {
            // Seed varied records for Somchai (somchai@example.com) -> Active borrowing & returned
            BorrowRecord::updateOrCreate(
                ['user_id' => $users['somchai@example.com']->id, 'book_id' => $books[0]->id],
                [
                    'status' => 'borrowed',
                    'borrowed_at' => now()->subDays(3),
                    'due_date' => now()->addDays(11),
                ]
            );
            BorrowRecord::updateOrCreate(
                ['user_id' => $users['somchai@example.com']->id, 'book_id' => $books[1]->id],
                [
                    'status' => 'returned',
                    'borrowed_at' => now()->subDays(20),
                    'due_date' => now()->subDays(6),
                    'returned_at' => now()->subDays(5),
                ]
            );

            // Seed varied records for Anan (anan@example.com) -> Overdue loan & pending request
            BorrowRecord::updateOrCreate(
                ['user_id' => $users['anan@example.com']->id, 'book_id' => $books[2]->id],
                [
                    'status' => 'borrowed',
                    'borrowed_at' => now()->subDays(18),
                    'due_date' => now()->subDays(4), // Overdue!
                ]
            );
            BorrowRecord::updateOrCreate(
                ['user_id' => $users['anan@example.com']->id, 'book_id' => $books[3]->id],
                [
                    'status' => 'pending',
                    'borrowed_at' => now(),
                    'due_date' => now()->addDays(14),
                ]
            );

            // Seed varied records for Napha (napha@example.com) -> Pending & active loan
            BorrowRecord::updateOrCreate(
                ['user_id' => $users['napha@example.com']->id, 'book_id' => $books[4]->id],
                [
                    'status' => 'borrowed',
                    'borrowed_at' => now()->subDays(1),
                    'due_date' => now()->addDays(13),
                ]
            );
            if ($books->count() >= 6) {
                BorrowRecord::updateOrCreate(
                    ['user_id' => $users['napha@example.com']->id, 'book_id' => $books[5]->id],
                    [
                        'status' => 'pending',
                        'borrowed_at' => now(),
                        'due_date' => now()->addDays(14),
                    ]
                );
            }

            // Seed varied records for Kittipong (kittipong@example.com) -> Multiple returned books & active
            if ($books->count() >= 8) {
                BorrowRecord::updateOrCreate(
                    ['user_id' => $users['kittipong@example.com']->id, 'book_id' => $books[6]->id],
                    [
                        'status' => 'returned',
                        'borrowed_at' => now()->subDays(30),
                        'due_date' => now()->subDays(16),
                        'returned_at' => now()->subDays(15),
                    ]
                );
                BorrowRecord::updateOrCreate(
                    ['user_id' => $users['kittipong@example.com']->id, 'book_id' => $books[7]->id],
                    [
                        'status' => 'borrowed',
                        'borrowed_at' => now()->subDays(5),
                        'due_date' => now()->addDays(9),
                    ]
                );
            }

            // Seed varied records for Librarian User (librarian@example.com) -> Regular user borrowing
            BorrowRecord::updateOrCreate(
                ['user_id' => $users['librarian@example.com']->id, 'book_id' => $books[1]->id],
                [
                    'status' => 'borrowed',
                    'borrowed_at' => now()->subDays(4),
                    'due_date' => now()->addDays(10),
                ]
            );
            BorrowRecord::updateOrCreate(
                ['user_id' => $users['librarian@example.com']->id, 'book_id' => $books[2]->id],
                [
                    'status' => 'returned',
                    'borrowed_at' => now()->subDays(25),
                    'due_date' => now()->subDays(11),
                    'returned_at' => now()->subDays(10),
                ]
            );
        }

        $this->call(BadgeSeeder::class);
        $this->call(JourneyHistorySeeder::class);
    }
}
