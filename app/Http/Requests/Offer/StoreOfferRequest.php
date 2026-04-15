<?php

namespace App\Http\Requests\Offer;

use Illuminate\Foundation\Http\FormRequest;

class StoreOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'offer_price' => ['required', 'integer', 'min:1'],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }
}
