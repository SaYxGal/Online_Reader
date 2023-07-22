<?php

namespace App\Http\Controllers;

use App\Http\Requests\Genre\DataRequest;
use App\Http\Resources\GenreResource;
use App\Models\Genre;
use App\Services\GenreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GenreController extends BaseController
{
    public function __construct(GenreService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return $this->service->index([]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DataRequest $request): JsonResponse|GenreResource
    {
        return $this->service->store($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function get(Genre $genre): GenreResource
    {
        return new GenreResource($genre);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DataRequest $request, Genre $genre): JsonResponse|GenreResource
    {
        return $this->service->update($request->validated(), $genre);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Genre $genre): GenreResource
    {
        $genreResource = new GenreResource($genre);
        $genre->delete();
        return $genreResource;
    }
}
