<?php

namespace App\Models;

use App\Observers\BookObserver;
use Database\Factories\BookFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[ObservedBy([BookObserver::class])]
class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'title', 'author', 'cover_image', 'description', 'stock',
    ];

    protected static function newFactory(): BookFactory
    {
        return BookFactory::new();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function borrowRecords(): HasMany
    {
        return $this->hasMany(BorrowRecord::class);
    }

    public function coverImageUrl(): ?string
    {
        if ($this->cover_image === null) {
            return null;
        }

        return str_contains($this->cover_image, '://')
            ? $this->cover_image
            : Storage::disk('public')->url($this->cover_image);
    }
}
