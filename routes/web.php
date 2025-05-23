<?php

use App\Controller\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartItemsController;


Route::get('/registration', [\App\Http\Controllers\UserController::class, 'getSignUpForm'])->name('registration');
Route::post('/registration', [\App\Http\Controllers\UserController::class, 'registration'])->name('registration.submit');

Route::get('/login', [\App\Http\Controllers\UserController::class, 'getLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\UserController::class, 'login'])->name('login.submit');

Route::middleware('auth')->group( function () {

    Route::get('catalog', [\App\Http\Controllers\ProductController::class, 'getCatalog'])->name('catalog');
    Route::get('product/{id}', [\App\Http\Controllers\ProductController::class, 'getProduct'])->name('product');

    Route::get('cartItems', [\App\Http\Controllers\CartItemsController::class, 'getCartItems'])->name('cartItems');
    Route::post('addToCart', [\App\Http\Controllers\CartItemsController::class, 'addToCart'])->name('addToCart');

    Route::post('deleteProduct', [\App\Http\Controllers\CartItemsController::class, 'deleteProduct'])->name('deleteProduct');
    Route::post('deleteCart', [\App\Http\Controllers\CartItemsController::class, 'deleteCart'])->name('deleteCart');

    Route::get('orders', [\App\Http\Controllers\OrdersController::class, 'getOrders'])->name('orders');
    Route::post('orders', [\App\Http\Controllers\OrdersController::class, 'addOrder'])->name('addOrder');

    Route::get('userProfile', [\App\Http\Controllers\UserController::class, 'getUserProfile'])->name('userProfile');

    Route::post('logout', [\App\Http\Controllers\UserController::class, 'logout'])->name('logout');

    Route::post('addReview', [\App\Http\Controllers\ProductController::class, 'addReview'])->name('addReview');

});


