<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $bookId = $request->book->user_id;
        if (auth()->check() && auth()->id() == $bookId) {
            return $next($request);
        } else {
            return \response()->json(["message" => "This user isn't allowed access to object"], 403);
        }
    }
}
