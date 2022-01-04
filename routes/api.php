<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Http\Request;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('tag')->group(function () {
    Route::get('/', [TagController::class, 'index']);
    Route::post('/', [TagController::class, 'create']);
    Route::get('/{tag_id}', [TagController::class, 'detail']);
    Route::patch('/{tag_id}', [TagController::class, 'update']);
    Route::delete('/{tag_id}', [TagController::class, 'delete']);
});

Route::prefix('news')->group(function () {
    Route::get('/', [ArticleController::class, 'index']);
    Route::post('/', [ArticleController::class, 'create']);
    Route::get('/{news_id}', [ArticleController::class, 'detail']);
    Route::patch('/{news_id}', [ArticleController::class, 'update']);
    Route::delete('/{news_id}', [ArticleController::class, 'delete']);
});
