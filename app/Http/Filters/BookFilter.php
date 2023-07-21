<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class BookFilter extends AbstractFilter
{
    public const TITLE = 'title';
    public const GENRES_ID = 'genres';

    protected function getCallbacks(): array
    {
        return [
            self::TITLE => [$this, 'title'],
            self::GENRES_ID => [$this, 'genres']
        ];
    }

    public function title(Builder $builder, $value): void
    {
        $builder->where('title', 'like', "%{$value}%");
    }

    public function genres(Builder $builder, $values): void
    {
        $builder->whereHas('genres', function (Builder $query) use ($values) {
            $query->whereIn('genres.id', $values);
        }, '>=', count($values));
    }
}
