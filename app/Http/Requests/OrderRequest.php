<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'user_id' => 'required',
            'address' => 'required|string',
            'phone' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'пользователь не опрделен',
            'address.required' => 'Address is required.',
            'address.string' => 'Address must be string.',
            'phone.required' => 'Contact Phone is required.',
        ];
    }
}
