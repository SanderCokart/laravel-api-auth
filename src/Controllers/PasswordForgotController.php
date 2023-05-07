<?php

namespace SanderCokart\LaravelApiAuth\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use SanderCokart\LaravelApiAuth\Requests\PasswordForgotRequest;

class PasswordForgotController extends Controller
{
    public function __invoke(PasswordForgotRequest $request): JsonResponse
    {
        $user = User::where('email', $request->safe()->only('email'))->first();

        $user?->sendPasswordResetNotification();

        return response()->json([
            'message' => 'If an account with that email exists, you will receive an email with a link to reset your password.',
        ]);
    }
}
