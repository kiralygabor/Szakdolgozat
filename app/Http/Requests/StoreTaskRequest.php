<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Normalize inputs before validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->input('task_type') === 'online') {
            $this->merge([
                'location' => 'Remote',
            ]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Will be handled by auth middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
{
    return [
        'title' => ['required','string','max:150'],
        'description' => ['required','string'],
        'price' => ['required','integer','min:5','max:5000'],
            // When task_type is online, prepareForValidation sets location to "Online"
            'location' => ['required_if:task_type,in-person','string','max:150'],
        'task_type' => ['required','in:in-person,online'],
        'required_date' => $this->isMethod('post') ? ['nullable','date','after_or_equal:today'] : ['nullable','date'],
        'required_before_date' => $this->isMethod('post') ? ['nullable','date','after_or_equal:today'] : ['nullable','date'],
        'is_date_flexible' => ['boolean'],
        'preferred_time' => ['nullable', 'array'],
        'preferred_time.*' => ['in:morning,midday,afternoon,evening'],
        'jobs_id' => ['required', 'exists:jobs,id'],
        'photos.*' => ['nullable','image','max:5120'],
        'employee_id' => ['nullable', 'exists:users,id'],
    ];
}
}