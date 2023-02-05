<?php

namespace SanderCokart\LaravelApiAuth\Contracts;

interface CanResetPasswordContract
{
    public function sendPasswordResetNotification(): void;
}
