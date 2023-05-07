<?php

namespace SanderCokart\LaravelApiAuth\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use SanderCokart\LaravelApiAuth\Requests\RegisterRequest;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        $user->sendEmailVerificationNotification();

        return response()->json(
            ['message' => 'Your account has been registered, please login and verify your email.'],
            201
        );
    }
}
