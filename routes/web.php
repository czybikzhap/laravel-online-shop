<?php

use App\Controller\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartItemsController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/registration', [\App\Http\Controllers\UserController::class, 'getSignUpForm']);
Route::post('/registration', [\App\Http\Controllers\UserController::class, 'registration']);

Route::get('/login', [\App\Http\Controllers\UserController::class, 'getLoginForm']);
Route::post('/login', [\App\Http\Controllers\UserController::class, 'login']);

Route::get('/catalog', [\App\Http\Controllers\ProductController::class, 'getCatalog']);

Route::get('/cartItems', [\App\Http\Controllers\CartItemsController::class, 'getCartItems']);
Route::post('/addToCart', [\App\Http\Controllers\CartItemsController::class, 'addToCart']);

Route::post('logout', [\App\Http\Controllers\UserController::class, 'logout']);

Route::post('deleteProduct', [\App\Http\Controllers\CartItemsController::class, 'deleteProduct']);

Route::post('deleteCart', [\App\Http\Controllers\CartItemsController::class, 'deleteCart']);

