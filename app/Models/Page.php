<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    use HasFactory, HasUuids;

    protected $with = ['comments'];
    protected $guarded = [];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'page_id', 'id');
    }
}
