<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class ReviewEmployerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'stars' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ];
    }
}
