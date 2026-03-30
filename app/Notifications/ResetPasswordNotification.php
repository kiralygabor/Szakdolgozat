<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordParent;

class ResetPasswordNotification extends ResetPasswordParent
{
    use Queueable;

    public $token;
    public $locale;

    /**
     * Create a new notification instance.
     */
    public function __construct($token, $locale = null)
    {
        $this->token = $token;
        $this->locale = $locale ?? app()->getLocale();
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $locale = $this->locale ?? $notifiable->locale ?? app()->getLocale();
        app()->setLocale($locale);
        
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject(__('emails.reset_password.subject'))
            ->view('emails.reset-password', [
                'url' => $url,
                'user' => $notifiable,
                'count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire'),
                'locale' => $locale,
            ]);
    }
}
