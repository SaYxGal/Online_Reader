<?php

namespace App\Services;

use App\Http\Filters\BookFilter;
use App\Http\Resources\Book\BookInfoResource;
use App\Http\Resources\Book\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class BookService
{
    public function index($data): AnonymousResourceCollection
    {
        $page = $data['page'] ?? 1;
        $perPage = $data['perPage'] ?? 20;
        unset($data['page'], $data['perPage']);
        $filter = app()->make(BookFilter::class, ['queryParams' => array_filter($data)]);
        $books = Book::with('genres')::filter($filter)->paginate($perPage, ['*'], 'page', $page);
        return BookResource::collection($books);
    }

    public function store($data): JsonResponse|BookInfoResource
    {
        try {
            DB::beginTransaction();
            $genres = $data['genres'];
            unset($data['genres']);
            $data['user_id'] = auth()->id();
            $book = Book::create($data);
            $book->genres()->attach($genres);
            DB::commit();
            return new BookInfoResource($book);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    public function update($data, $item): JsonResponse|BookInfoResource
    {
        try {
            DB::beginTransaction();
            $item->title = $data['title'];
            $item->description = $data['description'];
            $item->genres()->sync($data['genres']);
            $item->save();
            DB::commit();
            return new BookInfoResource($item);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    public function delete(Book $item): BookInfoResource|JsonResponse
    {
        $bookResource = new BookInfoResource($item);
        return $item->delete() ? $bookResource : response()->json(['message' => 'Not deleted']);
    }
}
