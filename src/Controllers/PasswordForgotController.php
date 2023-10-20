<?php

namespace SanderCokart\LaravelApiAuth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use SanderCokart\LaravelApiAuth\Models\ExampleUser as User;
use SanderCokart\LaravelApiAuth\Requests\PasswordForgotRequest;
use SanderCokart\LaravelApiAuth\Traits\ResolvesFrontendName;

class PasswordForgotController extends Controller
{
    use ResolvesFrontendName;

    /**
     * @throws BindingResolutionException
     */
    public function __invoke(PasswordForgotRequest $request): JsonResponse
    {
        $user = User::where('email', $request->safe()->only('email'))->first();

        $user?->sendPasswordResetNotification($this->resolveFrontendName());

        return response()->json([
            'message' => 'If an account with that email exists, you will receive an email with a link to reset your password.',
        ]);
    }
}
