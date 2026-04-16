<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CommentDeleteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route("comment")->user_id === auth()->user()->id;
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
