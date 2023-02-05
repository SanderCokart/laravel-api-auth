<?php

namespace SanderCokart\LaravelApiAuth\Controllers\Auth;
;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use SanderCokart\LaravelApiAuth\Models\PasswordReset;
use SanderCokart\LaravelApiAuth\Requests\PasswordResetRequest;

class PasswordResetController extends Controller
{
    public function __invoke(PasswordResetRequest $request): JsonResponse
    {
        $record = PasswordReset::query()
            ->where($request->safe()->only('id', 'token'))
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

        $user->update($request->safe()->only('password'));

        $record->delete();

        return response()->json(['message' => 'Password reset successfully']);
    }
}
