<?php

namespace App\Http\Resources\Chapter;

use App\Http\Resources\Page\PageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChapterFullResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "title" => $this->title,
            "pages" => PageResource::collection($this->pages),
            "order" => $this->order
        ];
    }
}
