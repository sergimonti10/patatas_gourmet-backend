<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'description' => 'required|string',
            'price' => 'required|numeric',
            'weight' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg',
            'image2' => 'required|image|mimes:jpeg,png,jpg',
            'id_cut' => 'nullable|exists:cuts,id',
        ];
    }
}
