<?php

namespace SanderCokart\LaravelApiAuth\Middleware;

use Closure;
use Illuminate\Http\Request;
use SanderCokart\LaravelApiAuth\Traits\ResolvesFrontendName;
use URL;

class RootUrlMiddleware
{
    use ResolvesFrontendName;

    // This middleware is used to force the root url to be the origin of the request.
    //This is needed for the Notifications to generate links where the origin is the host url.
    //So a request from example.com turns makes route urls generated into example.com
    public function handle(Request $request, Closure $next)
    {
        URL::forceRootUrl($this->resolveFrontendName());
        return $next($request);
    }
}
