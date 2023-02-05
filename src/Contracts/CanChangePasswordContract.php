<?php

namespace SanderCokart\LaravelApiAuth\Contracts;

interface CanChangePasswordContract
{
    public function sendPasswordChangedNotification(): void;
}
