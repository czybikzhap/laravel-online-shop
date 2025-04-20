<?php

use App\Controller\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/registration', [\App\Http\Controllers\UserController::class, 'getSignUpForm']);
Route::post('/registration', [\App\Http\Controllers\UserController::class, 'registration']);

Route::get('/login', [\App\Http\Controllers\UserController::class, 'getLoginForm']);
Route::post('/login', [\App\Http\Controllers\UserController::class, 'login']);
