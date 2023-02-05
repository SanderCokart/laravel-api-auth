<?php

return [
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
    'routes'      => [
        'guest-routes'         => [
            'register' => \SanderCokart\LaravelApiAuth\Controllers\Auth\RegisterController::class,
            'login'    => \SanderCokart\LaravelApiAuth\Controllers\Auth\LoginController::class,
            'email'    => [
                'reset' => \SanderCokart\LaravelApiAuth\Controllers\Auth\EmailResetController::class,
            ],
            'password' => [
                'forgot' => \SanderCokart\LaravelApiAuth\Controllers\Auth\PasswordForgotController::class,
                'reset'  => \SanderCokart\LaravelApiAuth\Controllers\Auth\PasswordResetController::class,
            ],
        ],
        'authenticated-routes' => [
            'logout'   => \SanderCokart\LaravelApiAuth\Controllers\Auth\LogoutController::class,
            'email'    => [
                'verify' => \SanderCokart\LaravelApiAuth\Notifications\EmailVerificationNotification::class,
                'change' => \SanderCokart\LaravelApiAuth\Controllers\Auth\EmailChangeController::class,
            ],
            'password' => [
                'change' => \SanderCokart\LaravelApiAuth\Controllers\Auth\PasswordChangeController::class,
            ],
        ],
    ],
];
