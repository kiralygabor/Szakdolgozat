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

    public $user;
    public $tasksByCategory;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Collection $tasksByCategory)
    {
        $this->user = $user;
        $this->tasksByCategory = $tasksByCategory;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject(__('emails.task_digest.subject'))
            ->view('emails.task-digest');
    }
}
