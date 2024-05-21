<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'postal_code' => 'required|integer',
            'locality' => 'required|string',
            'province' => 'required|string',
            'street' => 'required|string',
            'number' => 'required|string',
            'floor' => 'nullable|string',
            'staircase' => 'nullable|string',
            'image' => 'nullable|string',
            'phone' => 'required|string',
            'role' => 'required|string|exists:roles,name',
        ];
    }
}
