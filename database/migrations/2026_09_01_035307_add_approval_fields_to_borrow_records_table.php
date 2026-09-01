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
        Schema::table('borrow_records', function (Blueprint $table) {
            $table->date('borrowed_at')->nullable()->change();
            $table->date('due_date')->nullable()->change();
            $table->foreignId('reviewed_by')->nullable()->after('book_id')->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable()->after('returned_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrow_records', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reviewed_by');
            $table->dropColumn('reviewed_at');
            $table->date('borrowed_at')->nullable(false)->change();
            $table->date('due_date')->nullable(false)->change();
        });
    }
};
