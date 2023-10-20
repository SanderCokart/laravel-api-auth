<?php

namespace SanderCokart\LaravelApiAuth\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use SanderCokart\LaravelApiAuth\Traits\GeneratesExpirationTimestamp;

class EmailVerificationNotification extends Notification
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
    public function __construct(string $url, string $frontEndUrl)
    {
        $this->url = $url;
        $this->frontendName = $frontEndUrl;
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
            ->subject(sprintf('%s - Email Verification', $this->frontendName))
            ->greeting("Hello {$notifiable->name}!")
            ->line('Please verify your email address by clicking the button below.')
            ->action('Verify', $this->url)
            ->line($this->generateExpirationTimestamp($notifiable, config('auth.expirations.email_verifications')))
            ->salutation(config('api-auth.salutation'));
    }
}
