<?php

namespace App\Http\Controllers;

use App\Http\Requests\Book\FilterRequest;
use App\Http\Requests\Book\StoreRequest;
use App\Http\Requests\Book\UpdateRequest;
use App\Http\Resources\Book\BookResource;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookController extends Controller
{
    private BookService $service;

    public function __construct(BookService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request): AnonymousResourceCollection
    {
        return $this->service->index($request->validated());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): JsonResponse|BookResource
    {
        return $this->service->store($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function get(Book $book): BookResource
    {
        return new BookResource($book);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Book $book): JsonResponse|BookResource
    {
        return $this->service->update($request->validated(), $book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Book $book): BookResource|JsonResponse
    {
        return $this->service->delete($book);
    }
}
