<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Chapter extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class, 'chapter_pages', 'chapter_id', 'page_id');
    }
}
