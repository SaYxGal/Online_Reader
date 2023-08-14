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
        if (isset($request->book)) {
            $itemId = $request->book->user_id;
        } else if (isset($request->comment)) {
            $itemId = $request->comment->user_id;
        }
        if ((isset($itemId) && auth()->check() && auth()->id() == $itemId) || auth()->user()->role == "ADMIN") {
            return $next($request);
        } else {
            return \response()->json(["message" => "This user isn't allowed access to object"], 403);
        }
    }
}
