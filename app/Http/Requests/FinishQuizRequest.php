<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FinishQuizRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'answers' => 'nullable|array',
            'answers.*' => 'required|integer|exists:quiz_answers,id',
        ];
    }
}
