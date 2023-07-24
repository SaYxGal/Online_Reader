<?php

namespace App\Http\Resources\Page;

use App\Http\Resources\Comment\CommentCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class PageResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'image' => $this->image,
            'comments' => new CommentCollection(
                new LengthAwarePaginator(
                    $this->comments,
                    $this->comments()->count(),
                    10
                )
            ),
        ];
    }
}
