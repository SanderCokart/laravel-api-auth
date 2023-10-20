<?php

namespace SanderCokart\LaravelApiAuth\Contracts;

interface MustVerifyEmailContract
{
    public function isVerified(): bool;

    public function markEmailAsVerified(): bool;

    public function sendEmailVerificationNotification(string $frontendName): void;
}
