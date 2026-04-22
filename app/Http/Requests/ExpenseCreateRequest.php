<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseCreateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            "title" => "required|string|max:255",
            "amount" => "required|numeric|min:0.01",
            "description" => "nullable|string",
            "benefactors" => "required|array|min:1",
            "benefactors.*" => "exists:users,id",
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            "benefactors.required" => "Please select at least one benefactor.",
            "benefactors.min" => "Please select at least one benefactor.",
        ];
    }
}
