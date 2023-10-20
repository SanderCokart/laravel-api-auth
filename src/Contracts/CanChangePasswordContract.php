<?php

namespace SanderCokart\LaravelApiAuth\Contracts;

interface CanChangePasswordContract
{
    public function sendPasswordChangedNotification(string $frontendName): void;
}
