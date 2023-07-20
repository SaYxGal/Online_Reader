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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->uuid('page_id')->nullable();
            $table->index('page_id', 'comment_page_idx');
            $table->foreign('page_id', 'comment_page_fk')
                ->on('pages')->references('id');
            $table->unsignedBigInteger('book_id')->nullable();
            $table->index('book_id', 'comment_book_idx');
            $table->foreign('book_id', 'comment_book_fk')
                ->on('books')->references('id');
            $table->uuid('user_id');
            $table->index('user_id', 'comment_user_idx');
            $table->foreign('user_id', 'comment_user_fk')
                ->on('users')->references('id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
