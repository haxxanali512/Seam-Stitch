<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PasswordsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubController;





Route::post('/register', [UsersController::class, 'registeration']);

Route::post('/login', [UsersController::class, 'login']);

Route::post('/forgot', [PasswordsController::class, 'forgot']);

Route::get('/showproduct', [ProductsController::class, 'showProduct']);
Route::get('/showall', [ProductsController::class, 'showAll']);
Route::get('/showone/{id}', [ProductsController::class, 'showRespective']);
Route::get('/categories', [CategoryController::class, 'showCategories']);
Route::get('/subcategories', [SubController::class, 'showsubCategories']);




Route::group(['middleware' => ['auth:sanctum']], function () {

 //   Route::get('/showall', [ProductsController::class, 'showAll']);

    Route::post('/logout', [UsersController::class, 'logout']);
});

