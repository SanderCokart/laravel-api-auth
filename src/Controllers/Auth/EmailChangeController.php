<?php

namespace SanderCokart\LaravelApiAuth\Controllers\Auth;

use App\Http\Controllers\Controller;
use SanderCokart\LaravelApiAuth\Requests\EmailChangeRequest;
use Illuminate\Http\JsonResponse;
use SanderCokart\LaravelApiAuth\Observers\UserObserver;

class EmailChangeController extends Controller
{
    /**
     * @param EmailChangeRequest $request
     *
     * @return JsonResponse
     * @see UserObserver for update logic
     */
    public function __invoke(EmailChangeRequest $request): JsonResponse
    {
        $request->user()->update($request->only('email'));

        return response()
            ->json(['message' => 'Email successfully changed. Please check your inbox for a verification link.']);
    }
}
