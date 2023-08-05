<?php

namespace App\Http\Requests\Rent;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'price' => 'required|between:0,99999.99',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
        ];
    }
}
