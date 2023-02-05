<?php

namespace SanderCokart\LaravelApiAuth\Traits;

use SanderCokart\LaravelApiAuth\Models\EmailChange;
use SanderCokart\LaravelApiAuth\Models\EmailVerification;
use SanderCokart\LaravelApiAuth\Models\PasswordChange;
use SanderCokart\LaravelApiAuth\Models\PasswordReset;

/** @mixin PasswordReset|EmailVerification|PasswordChange|EmailChange */
trait CanExpire
{
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isNotExpired(): bool
    {
        return ! $this->isExpired();
    }
}
