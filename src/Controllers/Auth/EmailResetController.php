<?php

namespace SanderCokart\LaravelApiAuth\Controllers\Auth;
;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use SanderCokart\LaravelApiAuth\Models\EmailChange;
use SanderCokart\LaravelApiAuth\Requests\EmailResetRequest;
use SanderCokart\LaravelApiAuth\Observers\UserObserver;

class EmailResetController extends Controller
{
    /**
     * @param EmailResetRequest $request
     *
     * @return JsonResponse
     * @see UserObserver IMPORTANT! This endpoint updates the user quietly
     */
    public function __invoke(EmailResetRequest $request): JsonResponse
    {
        $validatedData = $request->safe()->merge(['password' => bcrypt($request->safe()->password)]);

        $record = EmailChange::query()
            ->where($validatedData->only('id', 'token'))
            ->firstOr(fn() => abort(422, 'Invalid id and or token.'));

        if ($record->isExpired()) {
            $record->delete();
            abort(401, 'Token has expired.');
        }

        $user = User::query()
            ->findOr(
                $record->user_id,
                fn() => abort(422, 'The user associated with this record no longer exists.')
            );

        $user->updateQuietly($validatedData->only('email', 'password'));
        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Email address and password updated successfully. Please check your email for a verification link.',
        ]);
    }
}
