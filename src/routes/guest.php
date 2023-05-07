<?php

use Illuminate\Support\Facades\Route;
use SanderCokart\LaravelApiAuth\Controllers\{
    EmailResetController,
    LoginController,
    PasswordForgotController,
    PasswordResetController,
    RegisterController
};

//Macro alternative: Route::ApiAuthGuestRoutes(); // Does NOT include GET routes for views
Route::group(['prefix' => 'account', 'as' => 'account.', 'middleware' => 'guest'], function () {
    Route::post('/register', RegisterController::class)->name('register')
        ->middleware('api-auth.force-root-url-origin');

    Route::post('/login', LoginController::class)->name('login');

    //VIEW PLACEHOLDER
    //Route::get('/login', fn() => view('login'))->name('login.view');
    //Route::get('/register', fn() => view('register'))->name('register.view');

    Route::group(['prefix' => 'email', 'as' => 'email.'], function () {
        Route::patch('/reset', EmailResetController::class)->name('reset')
            ->middleware('api-auth.force-root-url-origin');

        //VIEW PLACEHOLDER
        //Route::get('/reset', fn() => view('email-reset'))->name('reset.view');
    });

    Route::group([
        'prefix'     => 'password',
        'as'         => 'password.',
        'middleware' => 'api-auth.force-root-url-origin',
    ], function () {
        Route::post('/forgot', PasswordForgotController::class)->name('forgot');
        Route::patch('/reset', PasswordResetController::class)->name('reset');

        //VIEW PLACEHOLDER
        //Route::get('/reset', fn() => view('password-reset'))->name('reset.view');
        //Route::get('/forgot', fn() => view('password-forgot'))->name('forgot.view');
    });
});
