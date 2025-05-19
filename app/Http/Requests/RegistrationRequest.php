<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:2|max:255|regex:/^[a-zA-Zа-яА-ЯёЁ\s]+$/u',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:4|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            "name.required" => "не указано имя",
            "name.min" => "имя должно иметь не менее 2 символа",
            "email.required" => "не указано email",
            "email.email" => "укажите почту",
            "email.unique" => "пользователь с таким email уже существует",
            "password.required" => "придумайте пароль",
            "password.min" => "пароль должен состоять не менее из 6 символов",
            "password.confirmed" => "пароли не совпадают",
        ];
    }
}
