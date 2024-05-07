<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'date_order' => 'required|date',
            'date_deliver' => 'nullable|date',
            'status' => 'required|in:pending,processing,shipped,completed,canceled',
            'total_price' => 'required|numeric',
            'total_products' => 'required|integer',
            'id_user' => 'required',
        ];
    }
}
