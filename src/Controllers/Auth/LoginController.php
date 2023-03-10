<?php

namespace SanderCokart\LaravelApiAuth\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use SanderCokart\LaravelApiAuth\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Laravel\Sanctum\NewAccessToken;

class LoginController extends Controller
{
    /**
     * Process a login request to the application.
     *
     * @throws ValidationException
     */
    public function __invoke(LoginRequest $request): JsonResponse|Response
    {

        if (EnsureFrontendRequestsAreStateful::fromFrontend($request)) {
            $this->authenticateFrontend($request);
            return response()->json(['message' => 'Logged in successfully'], 200);
        }

        $token = $this->issueToken($request);
        return response()->json(['token' => $token->plainTextToken]);
    }

    /**
     * If the request is from the frontend, we will authenticate the user using the web guard.
     *
     * @throws ValidationException
     */
    private function authenticateFrontend(LoginRequest $request): void
    {

        if (! auth()->attempt(
            $request->safe()->only('email', 'password'),
            $request->safe()->remember_me
        )) {
            throw ValidationException::
            withMessages([
                'email'    => 'Invalid credentials.',
                'password' => 'Invalid credentials.',
            ]);
        }
    }

    /**
     * Issue a token to the user from the request data.
     *
     * @throws ValidationException
     */
    private function issueToken(LoginRequest $request): NewAccessToken
    {
        $user = User::where($request->safe()->only('email'))->first();

        if (! $user || ! Hash::check($request->safe()->password, $user->password)) {
            throw ValidationException::withMessages([
                'email'    => 'Invalid credentials',
                'password' => 'Invalid credentials',
            ]);
        }

        return $user->createToken($request->userAgent());
    }
}
