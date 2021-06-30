<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PasswordsController;




Route::post('/register', [UsersController::class, 'registeration']);
Route::post('/login', [UsersController::class, 'login']);
Route::post('/forgot', [PasswordsController::class, 'forgot']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [UsersController::class, 'logout']);
});

