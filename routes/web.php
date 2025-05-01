<?php

use App\Controller\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartItemsController;


Route::get('/registration', [\App\Http\Controllers\UserController::class, 'getSignUpForm'])->name('registration');
Route::post('/registration', [\App\Http\Controllers\UserController::class, 'registration'])->name('registration.submit');

Route::get('/login', [\App\Http\Controllers\UserController::class, 'getLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\UserController::class, 'login'])->name('login.submit');

Route::get('/catalog', [\App\Http\Controllers\ProductController::class, 'getCatalog'])->name('catalog');

Route::get('/cartItems', [\App\Http\Controllers\CartItemsController::class, 'getCartItems'])->name('cartItems');
Route::post('/addToCart', [\App\Http\Controllers\CartItemsController::class, 'addToCart'])->name('addToCart');

Route::post('logout', [\App\Http\Controllers\UserController::class, 'logout'])->name('logout');

Route::post('deleteProduct', [\App\Http\Controllers\CartItemsController::class, 'deleteProduct'])->name('deleteProduct');

Route::post('deleteCart', [\App\Http\Controllers\CartItemsController::class, 'deleteCart'])->name('deleteCart');

Route::get('userProfile', [\App\Http\Controllers\UserController::class, 'getUserProfile'])->name('userProfile');

Route::middleware('auth')->get('orders',[\App\Http\Controllers\OrdersController::class, 'getOrders'])->name('orders');
Route::post('orders', [\App\Http\Controllers\OrdersController::class, 'addOrder'])->name('addOrder');
