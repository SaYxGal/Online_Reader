<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('book_chapters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id');
            $table->uuid('chapter_id');

            $table->index('book_id', 'book_chapter_book_idx');
            $table->index('chapter_id', 'book_chapter_chapter_idx');

            $table->foreign('book_id', 'book_chapter_book_fk')
                ->on('books')->references('id')->cascadeOnDelete();
            $table->foreign('chapter_id', 'book_chapter_chapter_fk')
                ->on('chapters')->references('id')->cascadeOnDelete();
            $table->unique('chapter_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_chapters');
    }
};
