<?php

namespace SanderCokart\LaravelApiAuth\Contracts;

interface CanChangeEmailContract
{
    public function sendEmailChangedNotification(string $frontendName): void;
}
