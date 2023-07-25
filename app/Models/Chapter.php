<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Chapter extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $guarded = [];

    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class, 'chapter_pages', 'chapter_id', 'page_id');
    }

    public function delete(): bool
    {
        try {
            DB::beginTransaction();
            $this->pages()->delete();
            parent::delete();
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }
}
