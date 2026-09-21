<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reading_stats', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedInteger('total_books_read')->default(0);
            $table->unsignedInteger('total_points')->default(0);
            $table->unsignedSmallInteger('current_streak')->default(0);
            $table->unsignedSmallInteger('longest_streak')->default(0);
            $table->unsignedInteger('on_time_returns')->default(0);
            $table->unsignedInteger('late_returns')->default(0);
            $table->unsignedSmallInteger('categories_explored')->default(0);
            $table->timestamp('last_calculated_at')->nullable();
            $table->timestamps();

            $table->index('total_points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reading_stats');
    }
};
