<?php

namespace App\Services;

use App\Http\Filters\BookFilter;
use App\Http\Resources\Book\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class BookService implements ServiceInterface
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

    public function store($data): JsonResponse|BookResource
    {
        try {
            DB::beginTransaction();
            $genres = $data['genres'];
            unset($data['genres']);
            $data['user_id'] = auth()->id();
            $book = Book::create($data);
            $book->genres()->attach($genres);
            DB::commit();
            return new BookResource($book->fresh('genres'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    public function update($data, $item): JsonResponse|BookResource
    {
        try {
            DB::beginTransaction();
            $item->title = $data['title'];
            $item->description = $data['description'];
            $item->genres()->sync($data['genres']);
            $item->save();
            DB::commit();
            return new BookResource($item);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => $exception->getMessage()]);
        }
    }
}
