<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettlementCreateRequest extends FormRequest
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
            "receiver_id" => "required|exists:users,id",
            "amount" => "required|numeric|min:0.01",
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            "receiver_id.required" => "Please select who you're paying.",
            "receiver_id.exists" => "The selected user does not exist.",
        ];
    }
}
