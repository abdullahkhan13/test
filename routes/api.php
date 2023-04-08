<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

//Route::middleware(['throttle:global'])->group(function () {
    Route::get('articles', [ArticleController::class, 'list'])->name('articles');
    Route::get('articles/{id}', [ArticleController::class, 'getById'])->name('article');
    Route::get('articles/{id}/like', [ArticleController::class, 'likes'])->name('likes');
    Route::get('articles/{id}/comment', [ArticleController::class, 'comments'])->name('comments');
    Route::get('articles/{id}/view', [ArticleController::class, 'views'])->name('views');
//});
