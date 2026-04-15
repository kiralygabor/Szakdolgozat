<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class ReCaptcha implements Rule
{
    private const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    public function passes($attribute, $value): bool
    {
        $response = Http::post(self::VERIFY_URL, [
            'secret' => config('services.recaptcha.secret'),
            'response' => $value,
        ]);

        return $response->json('success', false);
    }

    public function message(): string
    {
        return __('The Google reCAPTCHA verification failed.');
    }
}
