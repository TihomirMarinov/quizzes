<?php

namespace App\Http\Requests\Question;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'question' => 'required|string|max:255',
            'answers' => 'array',
            'answers.*.answer' => 'required|nullable|string|max:255',
            'answers.*.is_correct' => 'required|boolean',
        ];
    }
}
