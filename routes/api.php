<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [App\Http\Controllers\Auth\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Auth\AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return response()->json(\App\Http\Resources\UserResource::make($request->user()));
    });

    Route::post('/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout']);

    // Post routes
    Route::get('/posts', [\App\Http\Controllers\PostController::class, 'index']);
    Route::post('/posts', [\App\Http\Controllers\PostController::class, 'store']);
    Route::get('/posts/{post}', [\App\Http\Controllers\PostController::class, 'show']);
    Route::put('/posts/{post}', [\App\Http\Controllers\PostController::class, 'update']);
    Route::delete('/posts/{post}', [\App\Http\Controllers\PostController::class, 'destroy']);

    // Comments routes
    Route::get('/posts/{post}/comments', [\App\Http\Controllers\CommentsController::class, 'index']);
    Route::post('/posts/{post}/comments', [\App\Http\Controllers\CommentsController::class, 'store']);
    Route::get('/posts/{post}/comments/{comment}', [\App\Http\Controllers\CommentsController::class, 'show']);
    Route::put('/posts/{post}/comments/{comment}', [\App\Http\Controllers\CommentsController::class, 'update']);
    Route::delete('/posts/{post}/comments/{comment}', [\App\Http\Controllers\CommentsController::class, 'destroy']);
});


