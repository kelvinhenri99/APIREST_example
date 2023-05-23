<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['apiJwt'],
    'prefix' => 'auth'
], function ($router) {

    Route::get('/users', [UserController::class, 'index'])->name('index');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('show');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('update');
    Route::delete('/users/{id}', [UserController::class, 'delete'])->name('delete');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
});

Route::post('auth/users', [UserController::class, 'create'])->name('create');
Route::post('auth/login', [AuthController::class, 'login'])->name('login');