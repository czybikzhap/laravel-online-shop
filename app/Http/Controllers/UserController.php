<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController
{
    public function getSignUpForm()
    {
        return view('signUpForm');

    }
    public function registration(RegistrationRequest $request)
    {
        $data = $request->all();

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return response()->redirectTo('/login');

    }

    public function getLoginForm()
    {
        return view('loginForm');
    }

    public function login(LoginRequest $request)
    {
       if (Auth::attempt([
           'email' => $request->get('email'),
           'password' => $request->get('password')
       ])) {
           return response()->redirectTo('/catalog');
       } else {
           return back()->withErrors(['email' => 'Неверные учетные данные.']);
       }

    }

    public function getUserProfile()
    {
        $user = Auth::user();

        return view('userProfile', compact('user'));
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Выход из системы

        $request->session()->invalidate(); // Инвалидируем сессию
        $request->session()->regenerateToken(); // Регенерируем CSRF токен

        return redirect('/registration '); // Перенаправление на главную страницу или страницу входа
    }





}
