<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'question' => 'required|string|max:5000',
            'answer' => 'nullable|string|max:5000',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id',
            'answered_by' => 'required|exists:users,id',
        ];
    }
}
