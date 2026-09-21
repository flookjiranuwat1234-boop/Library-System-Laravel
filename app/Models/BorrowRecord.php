<?php

namespace App\Models;

use App\Observers\BorrowRecordObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([BorrowRecordObserver::class])]
class BorrowRecord extends Model
{
    protected $fillable = [
        'user_id', 'book_id', 'reviewed_by', 'borrowed_at', 'due_date', 'returned_at', 'reviewed_at', 'overdue_notified_at', 'status',
    ];

    protected function casts(): array
    {
        return [
            'borrowed_at' => 'date',
            'due_date' => 'date',
            'returned_at' => 'date',
            'reviewed_at' => 'datetime',
            'overdue_notified_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
