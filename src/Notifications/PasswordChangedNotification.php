<?php

namespace SanderCokart\LaravelApiAuth\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use SanderCokart\LaravelApiAuth\Traits\GeneratesExpirationTimestamp;

class PasswordChangedNotification extends Notification
{
    use Queueable, GeneratesExpirationTimestamp;

    public string $url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $url)
    {

        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param User $notifiable
     *
     * @return array
     */
    public function via(User $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param User $notifiable
     *
     * @return MailMessage
     */
    public function toMail(User $notifiable): MailMessage
    {
        $minutes = config('auth.expirations.password_resets');

        return (new MailMessage)
            ->subject(sprintf('%s - Password Changed', config('app.frontend_name')))
            ->greeting("Hello {$notifiable->name}!")
            ->line('You are receiving this email because your password has been changed.')
            ->line('if you did not change your password, please press the button below to reset your password.')
            ->line($this->generateExpirationTimestamp($notifiable, $minutes))
            ->action('This was not me!', $this->url)
            ->salutation('Kind regards, Sander Cokart');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param User $notifiable
     *
     * @return array
     */
    public function toArray(User $notifiable): array
    {
        return [

        ];
    }
}
