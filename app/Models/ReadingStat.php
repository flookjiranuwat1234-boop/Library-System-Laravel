<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'total_books_read',
    'total_points',
    'current_streak',
    'longest_streak',
    'on_time_returns',
    'late_returns',
    'categories_explored',
    'last_calculated_at',
])]
class ReadingStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_books_read',
        'total_points',
        'current_streak',
        'longest_streak',
        'on_time_returns',
        'late_returns',
        'categories_explored',
        'last_calculated_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_books_read' => 'integer',
            'total_points' => 'integer',
            'current_streak' => 'integer',
            'longest_streak' => 'integer',
            'on_time_returns' => 'integer',
            'late_returns' => 'integer',
            'categories_explored' => 'integer',
            'last_calculated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function currentLevel(): int
    {
        return (int) floor($this->total_points / 100) + 1;
    }

    public function pointsInCurrentLevel(): int
    {
        return $this->total_points % 100;
    }

    public function pointsToNextLevel(): int
    {
        return 100 - $this->pointsInCurrentLevel();
    }
}
