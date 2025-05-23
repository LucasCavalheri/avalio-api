<?php

namespace App\Http\Requests\Business;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateBusinessRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'state' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:255',
            'neighborhood' => 'sometimes|string|max:255',
            'street' => 'sometimes|string|max:255',
            'number' => 'sometimes|string|max:255',
            'zip_code' => 'sometimes|string|max:255',
            'complement' => 'sometimes|string|max:255',
            'reference' => 'sometimes|string|max:255',
        ];
    }
}
