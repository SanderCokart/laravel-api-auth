<?php

return [
    'frontend_name' => env('FRONTEND_NAME', 'config.api-auth.frontend_name'),
    'salutation_author_name' => 'Laravel API Auth',
    'options'     => [
        'timezones'     => [
            'enabled' => false,
        ],
    ],

    /**
     * The default expiration time for tokens in minutes.
     */
    'expirations' => [
        'default'             => 60,     // 1 hour
        'email_verifications' => 60,     // 1 hour
        'email_changes'       => 525600, // 1 year
        'password_resets'     => 525600, // 1 year
        'password_changes'    => 525600, // 1 year
    ],

    /**
     * Here you can specify what controllers and methods should be used for the routes.
     *
     * FORMATS: SomethingController:class or [SomethingController::class, 'method'] or \App\Http\Controllers\SomethingController::class
     * All default controllers are invokable.
     */
    'routes'      => [
        'guest-routes'         => [
            'register' => \SanderCokart\LaravelApiAuth\Controllers\RegisterController::class,
            'login'    => \SanderCokart\LaravelApiAuth\Controllers\LoginController::class,
            'email'    => [
                'reset' => \SanderCokart\LaravelApiAuth\Controllers\EmailResetController::class,
            ],
            'password' => [
                'forgot' => \SanderCokart\LaravelApiAuth\Controllers\PasswordForgotController::class,
                'reset'  => \SanderCokart\LaravelApiAuth\Controllers\PasswordResetController::class,
            ],
        ],
        'authenticated-routes' => [
            'logout'   => \SanderCokart\LaravelApiAuth\Controllers\LogoutController::class,
            'email'    => [
                'verify' => \SanderCokart\LaravelApiAuth\Notifications\EmailVerificationNotification::class,
                'change' => \SanderCokart\LaravelApiAuth\Controllers\EmailChangeController::class,
            ],
            'password' => [
                'change' => \SanderCokart\LaravelApiAuth\Controllers\PasswordChangeController::class,
            ],
        ],
    ],
];
