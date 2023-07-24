<?php

namespace App\Http\Resources\Comment;

use App\Http\Resources\Pagination\PaginatedCollection;
use Illuminate\Http\Request;

class CommentCollection extends PaginatedCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // Here we transform any item in paginated items to a resource

            'data' => $this->collection->transform(function ($comment) {
                return new CommentResource($comment);
            }),

            'pagination' => $this->pagination,
        ];
    }
}
