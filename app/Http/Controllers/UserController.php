<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Mail\TestMail;
use App\Models\User;
use App\Services\RabbitmqSevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use App\Jobs\SendUserNotification;

class UserController
{

    private RabbitmqSevice $rabbitmqService;

    public function __construct(RabbitmqSevice $rabbitmqService)
    {
        $this->rabbitmqService = $rabbitmqService;
    }
    public function getSignUpForm()
    {
        return view('signUpForm');
    }

    /**
     * @throws \Exception
     */
    public function registration(RegistrationRequest $request)
    {
        /** @var User $user  */   //добавление аннотации

        $data = $request->all();

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        SendUserNotification::dispatch($user);

        //$this->rabbitmqService->produce(['id' => $user->id], 'signUpEmail');

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
