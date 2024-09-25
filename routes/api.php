<?php

use App\Http\Controllers\Api\post\PostsController;
use App\Http\Controllers\Api\user\UsersController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/posts' , [PostsController::class , 'index']);
Route::post('/posts' , [PostsController::class , 'store']);
Route::get('/posts/{post}' , [PostsController::class , 'show']);
Route::patch('/posts/{post}' , [PostsController::class , 'update']);
Route::delete('/posts/{post}' , [PostsController::class , 'destroy']);

Route::resource('/users' , UsersController::class);
