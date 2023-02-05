<?php

namespace SanderCokart\LaravelApiAuth\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class LogoutController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        if (EnsureFrontendRequestsAreStateful::fromFrontend($request)) {
            auth()->guard('web')->logout();
            return response()->json(['message' => 'Logged out successfully'], 200);
        }

        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
