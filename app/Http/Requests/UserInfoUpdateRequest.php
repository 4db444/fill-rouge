<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserInfoUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route("user")->id === auth()->user()->id;
    }

    public function rules(): array
    {
        return [
            "first_name" => "required|string|min:2",
            "last_name" => "required|string|min:2",
            "bio" => "nullable|string",
        ];
    }
}
