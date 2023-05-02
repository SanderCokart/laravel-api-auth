<?php

namespace SanderCokart\LaravelApiAuth\Middleware;

use Closure;
use Illuminate\Http\Request;
use URL;

class RootUrlMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        URL::forceRootUrl($_SERVER['HTTP_ORIGIN'] ?? null);
        return $next($request);
    }
}
