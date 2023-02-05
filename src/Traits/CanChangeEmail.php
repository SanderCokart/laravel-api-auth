<?php

namespace SanderCokart\LaravelApiAuth\Traits;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use SanderCokart\LaravelApiAuth\Models\EmailChange;
use SanderCokart\LaravelApiAuth\Notifications\EmailChangedNotification;
use SanderCokart\LaravelApiAuth\Support\SecurityToken;

trait CanChangeEmail
{
    use Notifiable;

    /**
     * @throws BindingResolutionException
     */
    public function sendEmailChangedNotification(): void
    {
        /** @var User $this */
        $url = SecurityToken::generateUrlWithToken(
            EmailChange::class,
            config('auth.expirations.email_changes'),
            function (string $uuid, string $token) {
                return route('account.email.reset', [
                    'uuid'  => $uuid,
                    'token' => $token,
                ]);
            },
            $this,
        );

        $this->notify(new EmailChangedNotification($url));
    }
}
