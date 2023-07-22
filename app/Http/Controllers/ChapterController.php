<?php

namespace App\Http\Controllers;

use App\Http\Requests\Chapter\DataRequest;
use App\Http\Resources\Chapter\ChapterFullResource;
use App\Http\Resources\Chapter\ChapterInfoResource;
use App\Models\Book;
use App\Models\Chapter;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Book $book): AnonymousResourceCollection
    {
        return ChapterInfoResource::collection($book->chapters());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DataRequest $request, Book $book): ChapterFullResource|JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $pages = $data['pages'];
            unset($data['pages']);
            $chapter = Chapter::create($data);
            foreach ($pages as $key => $page_info) {
                $page = Page::create($page_info);
                $chapter->pages()->attach($page->id, ['order' => $key + 1]);
            }
            $book->chapters()->attach($chapter->id, ['order' => $book->chapters()->count() + 1]);
            DB::commit();
            return new ChapterFullResource($chapter);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => $exception->getMessage()]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function get(Chapter $chapter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chapter $chapter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Chapter $chapter)
    {
        //
    }
}
