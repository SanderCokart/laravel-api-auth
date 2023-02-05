<?php

namespace SanderCokart\LaravelApiAuth\Facades;

use Illuminate\Support\Facades\Facade;

class SecurityTokenFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'security.token';
    }
}
