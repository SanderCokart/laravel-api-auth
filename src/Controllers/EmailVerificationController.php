<?php

namespace SanderCokart\LaravelApiAuth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SanderCokart\LaravelApiAuth\Models\EmailVerification;
use SanderCokart\LaravelApiAuth\Requests\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    public function __invoke(EmailVerificationRequest $request): JsonResponse
    {
        $record = EmailVerification::query()
            ->where($request->safe()->only('uuid', 'token'))
            ->first();

        abort_if(! $record, 422, 'Invalid uuid and or token.');

        if ($record->isExpired()) {
            $record->delete();
            abort(401, 'Token has expired.');
        }

        $request->user()->markEmailAsVerified();

        return response()->json(['message' => 'Email verified successfully.']);
    }
}
