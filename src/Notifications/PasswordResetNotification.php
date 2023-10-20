<?php

namespace SanderCokart\LaravelApiAuth\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use SanderCokart\LaravelApiAuth\Traits\GeneratesExpirationTimestamp;

class PasswordResetNotification extends Notification
{
    use Queueable, GeneratesExpirationTimestamp;

    /**
     * @var string - url for the action button
     */
    public string $url;

    /**
     * @var string - name of the frontend e.g. example.com
     */
    public string $frontendName;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $url, string $frontendName)
    {

        $this->url = $url;
        $this->frontendName = $frontendName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param Authenticatable $notifiable
     *
     * @return array
     */
    public function via(Authenticatable $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param Authenticatable $notifiable
     *
     * @return MailMessage
     */
    public function toMail(Authenticatable $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(sprintf('%s - Password Reset', $this->frontendName))
            ->greeting("Hello {$notifiable->name}!")
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->line('If you did not request a password reset, no further action is required.')
            ->line($this->generateExpirationTimestamp($notifiable, config('auth.expirations.password_resets')))
            ->action('Reset Password', $this->url)
            ->salutation(config('api-auth.salutation'));
    }
}
