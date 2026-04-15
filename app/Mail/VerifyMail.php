<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $code
    ) {}

    public function build(): self
    {
        return $this->subject(__('emails.verify_code.subject'))
            ->view('emails.verify-code')
            ->with([
                'user' => $this->user,
                'code' => $this->code,
            ]);
    }
}
