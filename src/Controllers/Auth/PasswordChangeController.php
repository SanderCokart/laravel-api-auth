<?php

namespace SanderCokart\LaravelApiAuth\Controllers\Auth;

use App\Http\Controllers\Controller;
use SanderCokart\LaravelApiAuth\Requests\PasswordChangeRequest;
use Illuminate\Http\JsonResponse;

class PasswordChangeController extends Controller
{
    /**
     * @param PasswordChangeRequest $request
     *
     * @return JsonResponse
     * @see UserObserver for update logic
     */
    public function __invoke(PasswordChangeRequest $request): JsonResponse
    {
        abort_unless(
            $request->user()->update($request->safe()->only('password')),
            500,
            'Failed to update password'
        );

        return response()->json(['message' => 'Password changed successfully']);
    }
}
