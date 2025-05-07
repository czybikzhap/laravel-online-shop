<?php

use App\Controller\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartItemsController;


Route::get('/registration', [\App\Http\Controllers\UserController::class, 'getSignUpForm'])->name('registration');
Route::post('/registration', [\App\Http\Controllers\UserController::class, 'registration'])->name('registration.submit');

Route::get('/login', [\App\Http\Controllers\UserController::class, 'getLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\UserController::class, 'login'])->name('login.submit');

//Route::middleware('Auth')->group( function () {

    Route::middleware('auth')->get('catalog', [\App\Http\Controllers\ProductController::class, 'getCatalog'])->name('catalog');
    Route::middleware('Auth')->get('product/{id}', [\App\Http\Controllers\ProductController::class, 'getProduct'])->name('product');

    Route::middleware('auth')->get('cartItems', [\App\Http\Controllers\CartItemsController::class, 'getCartItems'])->name('cartItems');
    Route::middleware('auth')->post('addToCart', [\App\Http\Controllers\CartItemsController::class, 'addToCart'])->name('addToCart');

    Route::middleware('auth')->post('deleteProduct', [\App\Http\Controllers\CartItemsController::class, 'deleteProduct'])->name('deleteProduct');
    Route::middleware('auth')->post('deleteCart', [\App\Http\Controllers\CartItemsController::class, 'deleteCart'])->name('deleteCart');

    Route::middleware('auth')->get('orders', [\App\Http\Controllers\OrdersController::class, 'getOrders'])->name('orders');
    Route::middleware('auth')->post('orders', [\App\Http\Controllers\OrdersController::class, 'addOrder'])->name('addOrder');

    Route::middleware('auth')->get('userProfile', [\App\Http\Controllers\UserController::class, 'getUserProfile'])->name('userProfile');

    Route::middleware('auth')->post('logout', [\App\Http\Controllers\UserController::class, 'logout'])->name('logout');



//});


