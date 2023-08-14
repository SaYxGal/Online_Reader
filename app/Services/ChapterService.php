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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ChapterService
{
    public function store(Request $request, Book $book): JsonResponse|ChapterInfoResource
    {
        try {
            DB::beginTransaction();
            if (!$request->hasFile('pages')) throw new \Error('Files not found');
            $pages = $request->file('pages');
            $data = array();
            $data['title'] = $request->title;
            $data['order'] = $book->chapters()->count() + 1;
            $chapter = Chapter::create($data);
            foreach ($pages as $key => $image) {
                $page_info = array();
                $page_info['order'] = $key + 1;
                $page_info['image'] = Storage::disk('public')->put('/images', $image);
                $page = Page::create($page_info);
                $chapter->pages()->attach($page->id);
            }
            $book->chapters()->attach($chapter->id);
            DB::commit();
            return new ChapterInfoResource($chapter);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    public function get($data, string $chapterId): ChapterFullResource|JsonResponse
    {
        if (isset($data['chap_page'])) {
            $pageInfo = ChapterPage::firstWhere('order', $data['chap_page']);
            if (isset($pageInfo)) {
                return response()->json([
                    'content' => new PageResource(Page::find($pageInfo->page_id)),
                    'order' => $data['chap_page']
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

    public function delete(string $chapterId): ChapterInfoResource|JsonResponse
    {
        $chapter = Chapter::find($chapterId);
        $chapterResource = new ChapterInfoResource($chapter);
        return $chapter->delete() ? $chapterResource : response()->json(['message' => 'Not deleted']);
    }
}
