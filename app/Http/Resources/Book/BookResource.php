<?php

namespace App\Http\Resources\Book;

use App\Http\Resources\Chapter\ChapterInfoResource;
use App\Http\Resources\Genre\GenreResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'genres' => GenreResource::collection($this->genres),
            'chapters' => ChapterInfoResource::collection($this->chapters)
        ];
    }
}
