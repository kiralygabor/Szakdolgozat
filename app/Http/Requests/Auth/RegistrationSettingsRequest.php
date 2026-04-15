<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationSettingsRequest extends FormRequest
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
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'birthdate'     => 'nullable|date',
            'phone_number'  => 'nullable|string|max:20',
            'county_id'     => 'required|integer|exists:counties,id',
            'city_id'       => 'required|integer|exists:cities,id',
            'email_notifications' => 'nullable|boolean',
            'email_task_digest'   => 'nullable|boolean',
            'tracked_categories'  => 'nullable|array',
        ];
    }
}
