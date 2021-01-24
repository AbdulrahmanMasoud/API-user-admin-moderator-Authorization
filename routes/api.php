<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['auth:api'],'prefix' => 'user'], function () {
    Route::post('register',[UserController::class,'register'])->withoutMiddleware('auth:api');
    Route::post('login',[UserController::class,'login'])->name('login')->withoutMiddleware('auth:api');
    Route::get('posts',[PostController::class,'index']);
    Route::post('add-post',[PostController::class,'store']);
    Route::put('edit-post/{id}',[PostController::class,'update']);
    Route::delete('delete-post/{id}',[PostController::class,'destroy']);
    Route::post('logout',[UserController::class,'logout'])->withoutMiddleware('auth:api');
});
