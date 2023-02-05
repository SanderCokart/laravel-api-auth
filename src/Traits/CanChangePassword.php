<?php

namespace SanderCokart\LaravelApiAuth\Traits;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use SanderCokart\LaravelApiAuth\Models\PasswordChange;
use SanderCokart\LaravelApiAuth\Notifications\PasswordResetNotification;
use SanderCokart\LaravelApiAuth\Support\SecurityToken;

trait CanChangePassword
{
    use Notifiable;

    /**
     * @throws BindingResolutionException
     */
    public function sendPasswordResetNotification(): void
    {
        /** @var User $this */
        $url = SecurityToken::generateUrlWithToken(
            PasswordChange::class,
            config('auth.expirations.password_resets'),
            function (string $id, string $token) {
                return route('account.password.reset', [
                    'id'    => $id,
                    'token' => $token,
                ]);
            },
            $this,
        );

        $this->notify(new PasswordResetNotification($url));
    }
}
