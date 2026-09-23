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
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
        $admin->update([
            'name' => 'Admin User',
            'role' => 'admin',
        ]);

        $member = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );
        $member->update([
            'name' => 'Regular User',
            'role' => 'user',
        ]);

        $extraUsers = [
            ['name' => 'สมชาย ใจดี (Senior Dev)', 'email' => 'somchai@example.com', 'role' => 'user'],
            ['name' => 'อนันต์ สุขเสริฐ (System Architect)', 'email' => 'anan@example.com', 'role' => 'user'],
            ['name' => 'นภา วงศ์ไพศาล (Data Scientist)', 'email' => 'napha@example.com', 'role' => 'user'],
            ['name' => 'กิตติพงษ์ วรโชติ (DevOps Specialist)', 'email' => 'kittipong@example.com', 'role' => 'user'],
            ['name' => 'บรรณารักษ์ อาวุโส (Senior Librarian)', 'email' => 'librarian@example.com', 'role' => 'admin'],
        ];

        foreach ($extraUsers as $u) {
            User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make('password'),
                    'role' => $u['role'],
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
            ['title' => 'Clean Code', 'author' => 'Robert C. Martin', 'category_id' => $categoriesCollection[0]->id, 'stock' => 5],
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

        BorrowRecord::firstOrCreate(
            [
                'user_id' => $member->id,
                'book_id' => Book::first()->id,
                'status' => 'borrowed',
            ],
            [
                'borrowed_at' => now()->subDays(2),
                'due_date' => now()->addDays(12),
            ]
        );

        BorrowRecord::firstOrCreate(
            [
                'user_id' => $admin->id,
                'book_id' => Book::skip(1)->first()->id,
                'status' => 'overdue',
            ],
            [
                'borrowed_at' => now()->subDays(10),
                'due_date' => now()->subDays(2),
            ]
        );

        $this->call(BadgeSeeder::class);
        $this->call(JourneyHistorySeeder::class);
    }
}
