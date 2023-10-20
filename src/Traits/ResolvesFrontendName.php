<?php

namespace SanderCokart\LaravelApiAuth\Traits;

trait ResolvesFrontendName
{
    /**
     * Assuming the api has multiple frontends, this will return the origin host name.
     * So when making a request from example.com/something, this will return example.com.
     *
     * @return string - Will return the HTTP_ORIGIN header if it exists, otherwise it will return the app name from the config.
     */
    private function resolveFrontendName(): string
    {
        return $_SERVER['HTTP_ORIGIN'] ?? config('app.name');
    }
}
