<?php

namespace SanderCokart\LaravelApiAuth\Traits;

use Illuminate\Foundation\Auth\User;

trait GeneratesExpirationTimestamp
{
    /**
     * @param User $notifiable The user that is receiving the notification, is required to get user timezone
     * @param int  $minutes The amount of minutes the token is valid for
     *
     * @return string
     */
    public function generateExpirationTimestamp(User $notifiable, int $minutes): string
    {
        return sprintf('This link will expire in %s on %s',
            now()->toTimeRemaining($minutes),
            now($notifiable?->timezone)->toExpirationDate($minutes)
        );
    }
}
