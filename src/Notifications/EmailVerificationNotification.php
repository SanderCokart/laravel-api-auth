<?php

namespace SanderCokart\LaravelApiAuth\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use SanderCokart\LaravelApiAuth\Traits\GeneratesExpirationTimestamp;

class EmailVerificationNotification extends Notification
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
        $minutes = config('auth.expirations.email_verifications');

        return (new MailMessage)
            ->subject(sprintf('%s - Email Verification', config('app.frontend_name')))
            ->greeting("Hello {$notifiable->name}!")
            ->line('Please verify your email address by clicking the button below.')
            ->action('Verify', $this->url)
            ->line($this->generateExpirationTimestamp($notifiable, $minutes))
            ->salutation('Kind regards, Sander Cokart.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray(User $notifiable): array
    {
        return [
            //
        ];
    }
}
