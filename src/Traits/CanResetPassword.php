<?php

namespace SanderCokart\LaravelApiAuth\Traits;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use SanderCokart\LaravelApiAuth\Models\PasswordReset;
use SanderCokart\LaravelApiAuth\Notifications\PasswordChangedNotification;
use SanderCokart\LaravelApiAuth\Support\SecurityToken;

/** @mixin User */
trait CanResetPassword
{
    use Notifiable;

    /**
     * @throws BindingResolutionException
     */
    public function sendPasswordChangedNotification(string $frontendName): void
    {
        /** @var User $this */
        $url = SecurityToken::generateUrlWithToken(
            PasswordReset::class,
            config('auth.expirations.password_resets'),
            function (string $id, string $token) {
                return route('account.password.reset', [
                    'id'    => $id,
                    'token' => $token,
                ]);
            },
            $this,
        );

        $this->notify(new PasswordChangedNotification($url, $frontendName));
    }
}
