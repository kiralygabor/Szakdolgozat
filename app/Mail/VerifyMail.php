<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class VerifyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $code;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $code)
    {
        $this->user = $user;
        $this->code = $code;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Verify your account')
            ->view('emails.verify-code')
            ->with([
                'user' => $this->user,
                'code' => $this->code,
            ]);
    }
}
