<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PostCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "title" => "required|string|max:250",
            "content" => "required|string|max:1000",
            "address" => "nullable|string|max:250",
            "images" => "nullable|array|max:5",
            "images.*" => "image|mimes:jpg,jpeg,png,webp|max:2048"
        ];
    }
}
