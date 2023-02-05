<?php

namespace SanderCokart\LaravelApiAuth\Contracts;

interface CanChangeEmailContract
{
    public function sendEmailChangedNotification(): void;
}
