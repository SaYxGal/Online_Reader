<?php

namespace App\Services;

use App\Http\Resources\Comment\CommentCollection;
use App\Http\Resources\Comment\CommentResource;
use App\Models\Book;
use App\Models\ChapterPage;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CommentService
{
    public function storeOnPage($data, string $chapterId): CommentResource|JsonResponse
    {
        try {
            DB::beginTransaction();
            $data['user_id'] = auth()->id();
            $pageOrder = $data['chap_page'];
            unset($data['chap_page']);
            $chapterPage = ChapterPage::firstWhere(["order" => $pageOrder, "chapter_id" => $chapterId]);
            if (isset($chapterPage)) {
                $data['page_id'] = $chapterPage->page_id;
                $comment = Comment::create($data);
                DB::commit();
                return new CommentResource($comment);
            } else {
                DB::commit();
                return response()->json(['message' => 'Page was not found']);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    public function storeOnBook($data, Book $book): CommentResource|JsonResponse
    {
        try {
            DB::beginTransaction();
            $data['user_id'] = auth()->id();
            $data['book_id'] = $book->id;
            $comment = Comment::create($data);
            DB::commit();
            return new CommentResource($comment);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    public function getBookComments(Book $book): CommentCollection
    {
        return new CommentCollection(new LengthAwarePaginator(
            $book->comments,
            $book->comments()->count(),
            10
        ));
    }

    public function update($data, Comment $comment): CommentResource|JsonResponse
    {
        try {
            DB::beginTransaction();
            $comment->content = $data['content'];
            $comment->save();
            DB::commit();
            return new CommentResource($comment->fresh());
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    public function delete(Comment $comment): CommentResource|JsonResponse
    {
        try {
            DB::beginTransaction();
            $commentResource = new CommentResource($comment);
            $comment->delete();
            DB::commit();
            return $commentResource;
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => $exception->getMessage()]);
        }
    }
}
