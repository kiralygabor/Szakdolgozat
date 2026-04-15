<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class TaskDigestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Collection $tasksByCategory
    ) {}

    public function build(): self
    {
        return $this->subject(__('emails.task_digest.subject'))
            ->view('emails.task-digest');
    }
}
