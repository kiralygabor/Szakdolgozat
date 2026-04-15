<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'advertisement_id' => 'required|exists:advertisements,id',
            'description' => 'required|string|min:10|max:1000',
            'reported_account_id' => 'required|exists:users,id',
        ];
    }
}
