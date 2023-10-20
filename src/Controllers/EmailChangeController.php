<?php

namespace SanderCokart\LaravelApiAuth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SanderCokart\LaravelApiAuth\Observers\PasswordEmailChangeObserver;
use SanderCokart\LaravelApiAuth\Requests\EmailChangeRequest;

class EmailChangeController extends Controller
{
    /**
     * @param EmailChangeRequest $request
     *
     * @return JsonResponse
     * @see PasswordEmailChangeObserver for update logic
     */
    public function __invoke(EmailChangeRequest $request): JsonResponse
    {
        $request->user()->update($request->only('email'));

        return response()
            ->json(['message' => 'Email successfully changed. Please check your inbox for a verification link.']);
    }
}
