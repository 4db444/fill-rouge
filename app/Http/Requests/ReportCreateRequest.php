<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        // this is to prevent users from reporting themselfs hahahaha
        return $this->route("user")->id !== auth()->user()->id;
    }

    public function rules(): array
    {
        return [
            "message" => "required|string|min:10|max:500"
        ];
    }
}
