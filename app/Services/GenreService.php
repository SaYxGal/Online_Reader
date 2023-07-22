<?php

namespace App\Services;

use App\Http\Resources\Genre\GenreResource;
use App\Models\Genre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class GenreService implements ServiceInterface
{
    public function index($data): AnonymousResourceCollection
    {
        $genres = Genre::all();
        return GenreResource::collection($genres);
    }

    public function store($data): JsonResponse|GenreResource
    {
        try {
            DB::beginTransaction();
            $genre = Genre::create($data);
            DB::commit();
            return new GenreResource($genre);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    public function update($data, $item): JsonResponse|GenreResource
    {
        try {
            DB::beginTransaction();
            $item->name = $data['name'];
            $item->save();
            DB::commit();
            return new GenreResource($item);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => $exception->getMessage()]);
        }
    }
}
