<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserPasswordUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route("user")->id === auth()->user()->id;
    }

    public function rules(): array
    {
        return [
            "old_password" => "required|string|current_password",
            "new_password" => "required|min:8|confirmed"
        ];
    }
}
