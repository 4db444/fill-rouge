<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RequestCreationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();
        $post = $this->route("post");
        return $post->user_id !== $user->id;
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
