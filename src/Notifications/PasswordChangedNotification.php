<?php

namespace SanderCokart\LaravelApiAuth\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use SanderCokart\LaravelApiAuth\Traits\GeneratesExpirationTimestamp;

class PasswordChangedNotification extends Notification
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
            ->subject(sprintf('%s - Password Changed', $this->frontendName))
            ->greeting("Hello {$notifiable->name}!")
            ->line('You are receiving this email because your password has been changed.')
            ->line('if you did not change your password, please press the button below to reset your password.')
            ->line($this->generateExpirationTimestamp($notifiable, config('auth.expirations.password_resets')))
            ->action('This was not me!', $this->url)
            ->salutation(config('api-auth.salutation'));
    }
}
