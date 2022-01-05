<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Redis;
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

// test redis connection
Route::prefix('redis')->group(function () {
    Route::get('/', function() {
        try {
            $redis=Redis::connect('127.0.0.1',3306);
            return response()->json(['message' => 'redis working', 'data' => $redis], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'data' => []], 500);
        }
    });
});


Route::prefix('tag')->group(function () {
    Route::get('/', [TagController::class, 'index'])->middleware('cache.redis');
    Route::post('/', [TagController::class, 'create']);
    Route::get('/{tag_id}', [TagController::class, 'detail'])->middleware('cache.redis');
    Route::patch('/{tag_id}', [TagController::class, 'update']);
    Route::delete('/{tag_id}', [TagController::class, 'delete']);
});

Route::prefix('news')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->middleware('cache.redis');
    Route::post('/', [ArticleController::class, 'create']);
    Route::get('/{news_id}', [ArticleController::class, 'detail'])->middleware('cache.redis');
    Route::patch('/{news_id}', [ArticleController::class, 'update']);
    Route::delete('/{news_id}', [ArticleController::class, 'delete']);
});
