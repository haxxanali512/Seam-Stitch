<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;



Route::post('/register', [UsersController::class, 'registeration']);
Route::post('/login', [UsersController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [UsersController::class, 'logout']);
});

