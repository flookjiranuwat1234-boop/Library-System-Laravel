<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
