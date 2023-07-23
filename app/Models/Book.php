<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    use HasFactory, Filterable;

    protected $guarded = [];

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'book_genres', 'book_id', 'genre_id');
    }

    public function chapters(): BelongsToMany
    {
        return $this->belongsToMany(Chapter::class, 'book_chapters', 'book_id', 'chapter_id');
    }

    public function delete(): true
    {
        $chapters = $this->chapters()->get();
        foreach ($chapters as $chapter) {
            $chapter->pages()->delete();
        }
        $this->chapters()->delete();
        parent::delete();
        return true;
    }
}
