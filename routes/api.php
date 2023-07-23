<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\GenreController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);

});
Route::group([
    'middleware' => 'jwt.auth',
    'prefix' => 'books'
], function ($router) {
    Route::get('/', [BookController::class, 'index']);
    Route::get('/{book}', [BookController::class, 'get']);
    Route::post('/', [BookController::class, 'store']);
    Route::patch('/{book}', [BookController::class, 'update'])->middleware('user');
    Route::delete('/{book}', [BookController::class, 'delete'])->middleware('admin');

    Route::group([
        'prefix' => '{book}/chapters'
    ], function ($router) {
        Route::get('/', [ChapterController::class, 'index']);
        Route::get('/{chapterId}', [ChapterController::class, 'get']);
        Route::post('/', [ChapterController::class, 'store'])->middleware('user');
        Route::post('/{chapter}', [ChapterController::class, 'update'])->middleware('user');
        Route::delete('/{chapterId}', [ChapterController::class, 'delete'])->middleware('user');
    });
});
Route::group([
    'middleware' => 'jwt.auth',
    'prefix' => 'genres'
], function ($router) {
    Route::get('/', [GenreController::class, 'index']);
    Route::get('/{genre}', [GenreController::class, 'get']);
    Route::post('/', [GenreController::class, 'store'])->middleware('admin');
    Route::patch('/{genre}', [GenreController::class, 'update'])->middleware('admin');
    Route::delete('/{genre}', [GenreController::class, 'delete'])->middleware('admin');
});
