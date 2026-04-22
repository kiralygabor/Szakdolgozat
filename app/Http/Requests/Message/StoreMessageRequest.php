<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    private const MAX_MESSAGE_LENGTH = 1000;
    private const MAX_ATTACHMENT_SIZE_KB = 5120; // 5MB

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'body' => 'nullable|string|max:' . self::MAX_MESSAGE_LENGTH,
            'attachment' => 'nullable|file|max:' . self::MAX_ATTACHMENT_SIZE_KB . '|mimes:jpeg,png,jpg,gif,pdf,doc,docx',
        ];
    }
}
