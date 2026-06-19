<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $url = $this->resetUrl($notifiable);

        $count = config(
            'auth.passwords.' . config('auth.defaults.passwords') . '.expire',
            60
        );

        return (new MailMessage)
            ->subject('Reset Kata Sandi - DITMAWA Telkom University')
            ->view('emails.reset-password', [
                'name'  => $notifiable->name,
                'url'   => $url,
                'count' => $count,
            ]);
    }
}
