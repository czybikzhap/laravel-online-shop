<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            'product_id' => 'required|integer',
            'review' => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            "product_id.required" => "не указано id продукта",
            "product_id.integer" => "неправильное id продукта",
            "review.required" => "пустой отзыв",
            "review.string" => "отзыв должен быть строкой",
        ];
    }
}
