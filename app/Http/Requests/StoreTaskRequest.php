<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->input('task_type') === 'online') {
            $this->merge(['location' => 'Remote']);
        }
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $dateRule = $this->isMethod('post')
            ? ['nullable', 'date', 'after_or_equal:today']
            : ['nullable', 'date'];

        return [
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'price' => ['required', 'integer', 'min:5', 'max:5000'],
            'location' => ['required_if:task_type,in-person', 'string', 'max:150'],
            'task_type' => ['required', 'in:in-person,online'],
            'required_date' => $dateRule,
            'required_before_date' => $dateRule,
            'is_date_flexible' => ['boolean'],
            'preferred_time' => ['nullable', 'array'],
            'preferred_time.*' => ['in:morning,midday,afternoon,evening'],
            'jobs_id' => ['required', 'exists:jobs,id'],
            'photos.*' => ['nullable', 'image', 'max:5120'],
            'employee_id' => ['nullable', 'exists:users,id'],
        ];
    }
}