<?php

namespace SanderCokart\LaravelApiAuth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SanderCokart\LaravelApiAuth\Requests\PasswordChangeRequest;

class PasswordChangeController extends Controller
{
    /**
     * @param PasswordChangeRequest $request
     *
     * @return JsonResponse
     * @see PasswordEmailChangeObserver for update logic
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
