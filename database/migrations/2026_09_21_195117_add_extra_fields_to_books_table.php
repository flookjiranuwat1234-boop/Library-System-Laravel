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
        Schema::table('books', function (Blueprint $table) {
            $table->string('isbn')->nullable()->unique()->after('author');
            $table->string('publisher')->nullable()->after('isbn');
            $table->smallInteger('year')->nullable()->after('publisher');
            $table->integer('pages')->nullable()->after('year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['pages', 'year', 'publisher', 'isbn']);
        });
    }
};
