<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ArticleController;


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

Route::post('articles', [ArticleController::class, 'index']);
Route::get('articles/{id}', [ArticleController::class, 'view']);
Route::get('articles/{id}/comment', [ArticleController::class, 'comment']);
Route::get('articles/{id}/like', [ArticleController::class, 'like']);
Route::get('articles/{id}/view', [ArticleController::class, 'totalviews']);
Route::post('articles/{id}/comment/create', [ArticleController::class, 'newComment']);
Route::get('articles/{id}/like/increment', [ArticleController::class, 'updateLikes']);