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
        return $post->user_id !== $user->id 
        && 
        !$post->requests()->where("users.id", $user->id)->wherePivot("status", "accepted")->exists();
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
