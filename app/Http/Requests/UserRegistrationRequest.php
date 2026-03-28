<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRegistrationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "first_name" => "required|string|min:2",
            "last_name" => "required|string|min:2",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed|min:8",
        ];
    }
}
