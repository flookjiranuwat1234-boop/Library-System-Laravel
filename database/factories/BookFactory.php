<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name(),
            'cover_image' => 'https://images.unsplash.com/' . urlencode('photo-1512820790803-83ca734da794?auto=format&fit=crop&w=300&q=80'),
            'description' => $this->faker->paragraph(),
            'stock' => $this->faker->numberBetween(1, 10),
        ];
    }
}
