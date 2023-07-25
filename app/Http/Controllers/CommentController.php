<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\CommentRequest;
use App\Http\Resources\Comment\CommentCollection;
use App\Http\Resources\Comment\CommentResource;
use App\Models\Book;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    private CommentService $service;

    public function __construct(CommentService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function getBookComments(Book $book): CommentCollection
    {
        return $this->service->getBookComments($book);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentRequest $request, Book $book, string $chapterId = ''): CommentResource|JsonResponse
    {
        $data = $request->validated();
        if (isset($data['chap_page'])) {
            return $this->service->storeOnPage($data, $chapterId);
        }
        return $this->service->storeOnBook($request->validated(), $book);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentRequest $request, Comment $comment): CommentResource|JsonResponse
    {
        return $this->service->update($request->validated(), $comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Comment $comment): CommentResource|JsonResponse
    {
        return $this->service->delete($comment);
    }
}
