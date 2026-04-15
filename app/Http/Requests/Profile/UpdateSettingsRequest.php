<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'theme' => 'nullable|string|in:light,dark,system',
            'reduced_motion' => 'nullable|boolean',
            'high_contrast' => 'nullable|boolean',
        ];
    }
}
