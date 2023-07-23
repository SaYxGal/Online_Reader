<?php

namespace App\Services;

use App\Http\Resources\Chapter\ChapterFullResource;
use App\Http\Resources\Chapter\ChapterInfoResource;
use App\Http\Resources\Page\PageResource;
use App\Models\Book;
use App\Models\Chapter;
use App\Models\ChapterPage;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ChapterService
{
    public function index(Book $book)
    {
        dd($book);
        return ChapterInfoResource::collection($book->chapters);
    }

    public function store($data, Book $book): JsonResponse|ChapterInfoResource
    {
        try {
            DB::beginTransaction();
            $pages = $data['pages'];
            unset($data['pages']);
            $chapter = Chapter::create($data);
            foreach ($pages as $key => $page_info) {
                $page = Page::create($page_info);
                $chapter->pages()->attach($page->id, ['order' => $key + 1]);
            }
            $book->chapters()->attach($chapter->id, ['order' => $book->chapters()->count() + 1]);
            DB::commit();
            return new ChapterInfoResource($chapter);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    public function get($data, string $chapterId): ChapterFullResource|JsonResponse
    {
        if (isset($data['page'])) {
            $pageInfo = ChapterPage::firstWhere('order', $data['page']);
            if (isset($pageInfo)) {
                return response()->json([
                    'content' => new PageResource(Page::find($pageInfo->page_id)),
                    'order' => $data['page']
                ]);
            } else {
                return response()->json([
                    'message' => 'Page not found.'
                ], 404);
            }

        } else {
            return new ChapterFullResource(Chapter::find($chapterId));
        }
    }

    public function update($data, $item)
    {
        try {
            DB::beginTransaction();

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    public function delete(string $chapterId): ChapterInfoResource|JsonResponse
    {
        $chapter = Chapter::find($chapterId);
        $chapterResource = new ChapterInfoResource($chapter);
        return $chapter->delete() ? $chapterResource : response()->json(['message' => 'Not deleted']);
    }
}
