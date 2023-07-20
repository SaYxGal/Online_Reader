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
        Schema::create('chapter_pages', function (Blueprint $table) {
            $table->id();
            $table->uuid('chapter_id');
            $table->uuid('page_id');
            $table->integer('order');

            $table->index('chapter_id', 'chapter_page_chapter_idx');
            $table->index('page_id', 'chapter_page_page_idx');

            $table->foreign('chapter_id', 'chapter_page_chapter_fk')
                ->on('chapters')->references('id');
            $table->foreign('page_id', 'chapter_page_page_fk')
                ->on('pages')->references('id')->onDelete('cascade');
            $table->unique(array('chapter_id', 'page_id'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapter_pages');
    }
};
