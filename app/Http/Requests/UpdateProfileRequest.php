<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $userId],
            'phone_number' => ['nullable', 'string', 'max:50'],
            'birthdate' => ['nullable', 'date', 'before_or_equal:today'],
            'city_id' => ['nullable', 'integer', 'exists:cities,id'],
            'avatar' => ['nullable', 'image', 'max:5120'],
        ];
    }
}
