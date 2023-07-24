<?php

namespace App\Services;

use App\Http\Resources\Comment\CommentCollection;
use App\Http\Resources\Comment\CommentResource;
use App\Models\Book;
use App\Models\ChapterPage;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class CommentService
{
    public function storeOnPage($data, string $chapterId): CommentResource|JsonResponse
    {
        $data['user_id'] = auth()->id();
        $pageOrder = $data['chap_page'];
        unset($data['chap_page']);
        $chapterPage = ChapterPage::firstWhere(["order" => $pageOrder, "chapter_id" => $chapterId]);
        if (isset($chapterPage)) {
            $data['page_id'] = $chapterPage->page_id;
            $comment = Comment::create($data);
            return new CommentResource($comment);
        } else {
            return response()->json(['message' => 'Page was not found']);
        }
    }

    public function storeOnBook($data, Book $book)
    {
        $data['user_id'] = auth()->id();
        $data['book_id'] = $book->id;
        $comment = Comment::create($data);
        return new CommentResource($comment);
    }

    public function getBookComments(Book $book): CommentCollection
    {
        return new CommentCollection(new LengthAwarePaginator(
            $book->comments,
            $book->comments()->count(),
            10
        ));
    }

    public function delete()
    {

    }
}
