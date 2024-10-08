<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
   
Route::controller(AuthController::class)->group(function(){
    Route::post('auth/register', 'register');
    Route::post('auth/login', 'login');
});
         
Route::middleware('auth:sanctum')->group( function () {
    Route::controller(PostController::class)->group(function(){
        Route::get('/posts', 'index');
        Route::get('/posts/{id}', 'show');
        Route::get('/posts/{slug}/slug', 'showBySlug');
        Route::post('/posts', 'store');
        Route::patch('/posts/{id}', 'update');
        Route::delete('/posts/{id}', 'destroy');
    });
    Route::controller(UserController::class)->group(function(){
        Route::get('/user/detail/{id}', 'detail');
        Route::get('/user/profile', 'profile');
    });
    Route::controller(AuthController::class)->group(function(){
        Route::post('auth/logout', 'logout');
    });
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
