<?php

use App\Controller\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/registration', [\App\Http\Controllers\UserController::class, 'getSignUpForm']);
Route::post('/registration', [\App\Http\Controllers\UserController::class, 'registration']);

Route::get('/login', [\App\Http\Controllers\UserController::class, 'getLoginForm']);
Route::post('/login', [\App\Http\Controllers\UserController::class, 'login']);

Route::get('/catalog', [\App\Http\Controllers\ProductController::class, 'getCatalog']);

Route::get('/cartItems', [\App\Http\Controllers\CartItemsController::class, 'getCartItems']);
Route::post('/cartItems', [\App\Http\Controllers\CartItemsController::class, 'addToCartItems']);
