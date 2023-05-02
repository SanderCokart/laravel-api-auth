<?php

namespace SanderCokart\LaravelApiAuth\Traits;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use SanderCokart\LaravelApiAuth\Models\EmailVerification;
use SanderCokart\LaravelApiAuth\Notifications\EmailVerificationNotification;
use SanderCokart\LaravelApiAuth\Support\SecurityToken;

/** @mixin User */
trait MustVerifyEmail
{
    use Notifiable;

    public function initializeMustVerifyEmailCasts(): void
    {
        if (! isset($this->casts['email_verified_at'])) {
            $this->casts = array_merge($this->casts, [
                'email_verified_at' => 'datetime',
            ]);
        }
    }

    public function isVerified(): bool
    {
        return ! is_null($this->email_verified_at);
    }

    public function markEmailAsVerified(): bool
    {
        return $this->update([
            'email_verified_at' => $this->freshTimestamp(),
        ]);
    }

    /**
     * @throws BindingResolutionException
     */
    public function sendEmailVerificationNotification(): void
    {
        $url = SecurityToken::generateUrlWithToken(
            EmailVerification::class,
            config('auth.expirations.email_verifications'),
            function (string $uuid, string $token) {
                return route('account.email.verify', [
                    'uuid'  => $uuid,
                    'token' => $token,
                ]);
            },
        );

        $this->notify(new EmailVerificationNotification($url));
    }
}
