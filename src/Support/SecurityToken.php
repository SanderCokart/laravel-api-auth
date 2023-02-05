<?php

namespace SanderCokart\LaravelApiAuth\Support;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class SecurityToken
{
    /**
     * Generate a token
     *
     * @return string The generated token.
     */
    public static function generateToken(): string
    {
        return hash_hmac('sha256', Str::random(40), config('app.key'));
    }

    /**
     * Generate an url with an id and token.
     *
     * @param string                                             $model model class-string, e.g SanderCokart\LaravelApiAuth\Models\EmailVerification:class.
     * @param int                                                $minutes The amount of minutes the token should be valid for (default: 60).
     * @param Closure(string|int $id, string $token):string|null $urlGenerator A closure | signature: fn (string|int $id, string $token): string.
     * @param User|null                                          $user The user model whose id should be saved to the database, if no user is provided the user_id column will be omitted.
     * @param string|null                                        $token Override the token to use.
     * @param string|null                                        $id Override the id to use.
     *
     * @return string|null                                       The generated url or null if the url generator is not provided.
     * @throws BindingResolutionException
     */
    public static function generateUrlWithToken(string $model, int $minutes, Closure $urlGenerator = null, User $user = null, string $token = null, string $id = null): string|null
    {
        /** @var Model $model */
        $model = app()->make($model);

        $token = $token ?? self::generateToken();

        $data = [
            'token'      => $token,
            'expires_at' => now()->addMinutes($minutes),
        ];

        if ($id) {
            $data['id'] = $id;
        }

        $record = match (true) {
            ! is_null($user) => $model::updateOrCreate(['user_id' => $user->id], $data),
            default          => $model::create($data),
        };

        return is_callable($urlGenerator)
            ? $urlGenerator($record->id, $token)
            : null;
    }
}
