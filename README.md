# Laravel Api Auth

This package provides a simple way to authenticate users using an API. It uses Sanctum for authentication and has a few
extra features.
Such as password change, email change and email reset that are not included by default in laravel.

## Why was this package created?

I created this package because I needed a simple way to authenticate users using an API. I wanted to use Sanctum, but I
also wanted to have a few extra features.
Such as `password change`, `email change` and `email reset` that are not included by default in laravel.

This package is also easier to read and understand than the default laravel authentication when it comes
to how tokens are handled. There is a single service that handles URL generation and token generation.

Laravel uses a `PasswordBroker` to handle password resets and `DatabaseTokenRepository` but to reuse and reinvent these
to apply to email changes, resets and such was too cumbersome for me personally. So I have abstracted away all the logic into a
single reusable service.

I felt that it would be a waste of time to create these features for every project I work on.
So I decided to create this package.

I hope you find it useful as well.

<hr/>

## NOTE

I am a beginner when it comes to creating packages. So if you find any bugs or have any suggestions, please let me know.

Also, because I am a beginner, I am sure there are better ways to do things. So if you have any suggestions on how to
improve the code, please do pull requests.

And finally if you find this package useful, please consider giving it a star.

# Features

- Login
- Register
- Logout
- Email Verification
- Password Reset
- Password Change
- Email Change
- Email Reset

# Installation

You can install the package via composer:

```bash
composer require sandercokart/laravel-api-auth
```

## User intervention

# Publishing - Read carefully

## Publish all <span style="color: red">(dangerous)</span> - for starter projects
```bash
php artisan vendor:publish --provider="SanderCokart\LaravelApiAuth\LaravelApiAuthServiceProvider"
```

## <span style="color: yellow">Optional</span>

These are optional to publish. They are not required.

### Migrations

Only publish and run the migrations if you want to add `timezone` on the user model.

```bash
php artisan vendor:publish --provider="SanderCoKart\LaravelApiAuth\LaravelApiAuthServiceProvider" --tag="api-auth-migrations"
```

### Models

This package contains 4 models that you can use. Publish them if you want to change them.
Models are `EmailChange`, `EmailReset`, `PasswordReset` and `PasswordChange`.

```bash
php artisan vendor:publish --provider="SanderCoKart\LaravelApiAuth\LaravelApiAuthServiceProvider" --tag="api-auth-models"
```

### User Observer - <span style="color: red">IMPORTANT!</span>

<strong>If you do not publish this, you will have to manually send the necessary notifications.</strong>

This `UserObserver` will automatically hash the password when the user is `creating` or `updating`.
I feel like observers are more elegant than using events. \
`Tip from me:` use the `@see [NAME]Observer` phpdoc on classes that trigger the observer
like you can see in the example user model and publishable controllers.

```bash
php artisan vendor:publish --provider="SanderCoKart\LaravelApiAuth\LaravelApiAuthServiceProvider" --tag="api-auth-user-observer"
```

### Example User Model

This is an example user model that you can use to get started. It is however not required.

```bash
php artisan vendor:publish --provider="SanderCoKart\LaravelApiAuth\LaravelApiAuthServiceProvider" --tag="api-auth-example-user"
```

## <span style="color: green">Recommended</span>

The following are recommended to be published. They are however not required.

### Config

The config allows for customizing the controllers and expiration times.

```bash
php artisan vendor:publish --provider="SanderCoKart\LaravelApiAuth\LaravelApiAuthServiceProvider" --tag="api-auth-config"
```

### Models

```bash
php artisan vendor:publish --provider="SanderCoKart\LaravelApiAuth\LaravelApiAuthServiceProvider" --tag="api-auth-models"
```

### Controllers

```bash
php artisan vendor:publish --provider="SanderCoKart\LaravelApiAuth\LaravelApiAuthServiceProvider" --tag="api-auth-controllers"
```

### Requests

```bash
php artisan vendor:publish --provider="SanderCoKart\LaravelApiAuth\LaravelApiAuthServiceProvider" --tag="api-auth-requests"
```

### Notifications

```bash
php artisan vendor:publish --provider="SanderCoKart\LaravelApiAuth\LaravelApiAuthServiceProvider" --tag="api-auth-notifications"
```

### Routes

There are 2 route `macros` available, What they do is pretty self-explanatory.

```php
Route::ApiAuthGuestRoutes();

Route::ApiAuthAuthenticatedRoutes();
```

You can also publish the route files instead of using the macros.\
They will be located in `routes/vendor/`

When these are published you can move them into the `routes` folder and modify them to your liking.\


```bash
php artisan vendor:publish --provider="SanderCoKart\LaravelApiAuth\LaravelApiAuthServiceProvider" --tag="api-auth-routes"
```

I also want to gift you this RouteServiceProvider routes configuration. Just copy and replace it in your `RouteServiceProvider.php`.
If you use this you can remove middleware from the routes files.

```php
        $this->routes(function () {
            Route::middleware(['api'])->group(function () {
                Route::namespace($this->namespace)
                    /** @see ../../routes/guest.php */
                    ->group(base_path('routes/guest.php'));

                Route::namespace($this->namespace)
                    ->middleware(['auth:sanctum'])
                    /** @see ../../routes/authenticated.php */
                    ->group(base_path('routes/authenticated.php'));

                Route::namespace($this->namespace)
                    ->middleware(['auth:sanctum', 'verified'])
                    /** @see ../../routes/verified.php */
                    ->group(base_path('routes/verified.php'));
            });
        });
```

```bash

# Under the hood of the package

> There is a facade available for the service.
It contains all the logic for generating URLs and tokens.
Alternatively you can use the service directly. `SanderCokart\LaravelApiAuth\Support\SecurityToken`

```php
use SecurityToken;

class MyClass
{

    public function example(): void
    {
        /** hash_hmac('sha256', Str::random(40), config('app.key')); */
        $token SecurityToken::generateToken();

        $url = SecurityToken::generateUrlWithToken(
                            string $model, // e.g EmailChange::class
                            int $minutes,
                            Closure $urlGenerator = null,
                            User $user = null, // if the model has a user_id column
                            string $token = null, // if you want to use a custom token
                            string $id = null // if you want to use a custom id, can be omitted if the model has an id column
                ): string|null

        // Example $urlGenerator
        $urlGenerator = function (string $id, string $token) {
            return route('email.change', [
                'id' => $id,
                'token' => $token,
            ]);
        };
    }
}
```
