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
        Schema::create('book_genres', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('genre_id');

            $table->index('book_id', 'book_genre_book_idx');
            $table->index('genre_id', 'book_genre_genre_idx');
            $table->foreign('book_id', 'book_genre_book_fk')
                ->on('books')->references('id')->cascadeOnDelete();
            $table->foreign('genre_id', 'book_genre_genre_fk')
                ->on('genres')->references('id')->cascadeOnDelete();
            $table->unique(array('book_id', 'genre_id'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_genres');
    }
};
