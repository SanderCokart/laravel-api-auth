<?php

namespace SanderCokart\LaravelApiAuth\Facades;

use Illuminate\Support\Facades\Facade;

class SecurityToken extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'security.token';
    }
}
