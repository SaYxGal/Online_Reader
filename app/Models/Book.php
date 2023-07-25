<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

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

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'book_id', 'id');
    }

    public function delete(): bool
    {
        try {
            DB::beginTransaction();
            $chapters = $this->chapters()->get();
            foreach ($chapters as $chapter) {
                $chapter->pages()->delete();
            }
            $this->chapters()->delete();
            parent::delete();
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }
}
