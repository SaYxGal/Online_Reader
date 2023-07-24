<?php

namespace App\Http\Controllers;

use App\Http\Requests\Chapter\DataRequest;
use App\Http\Requests\Chapter\GetRequest;
use App\Http\Resources\Chapter\ChapterFullResource;
use App\Http\Resources\Chapter\ChapterInfoResource;
use App\Models\Book;
use App\Services\ChapterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChapterController extends Controller
{
    private ChapterService $service;

    public function __construct(ChapterService $service)
    {
        $this->service = $service;
    }

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
    public function store(DataRequest $request, Book $book): ChapterInfoResource|JsonResponse
    {
        return $this->service->store($request->validated(), $book);
    }

    /**
     * Display the specified resource.
     */
    public function get(GetRequest $request, Book $book, string $chapterId): ChapterFullResource|JsonResponse
    {
        return $this->service->get($request->validated(), $chapterId);
    }

    public function delete(Book $book, string $chapterId): ChapterInfoResource|JsonResponse
    {
        return $this->service->delete($chapterId);
    }
}
