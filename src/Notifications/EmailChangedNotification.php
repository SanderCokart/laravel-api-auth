<?php

namespace SanderCokart\LaravelApiAuth\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use SanderCokart\LaravelApiAuth\Traits\GeneratesExpirationTimestamp;

class EmailChangedNotification extends Notification
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
        $minutes = config('auth.expirations.email_changes');

        return (new MailMessage)
            ->subject(sprintf('%s - Email Changed', config('api-auth.frontend_name')))
            ->greeting("Hello {$notifiable->name}!")
            ->line('Your email address has been changed.')
            ->line('If you did not change your email address, press the button below to reset your email and password.')
            ->line($this->generateExpirationTimestamp($notifiable, $minutes))
            ->action('This was not me!', $this->url)
            //TODO replace hardcoded  with config
            ->salutation(config('api-auth.salutation'));
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
