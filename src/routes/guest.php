<?php

use Illuminate\Support\Facades\Route;
use SanderCokart\LaravelApiAuth\Controllers\Auth\EmailResetController;
use SanderCokart\LaravelApiAuth\Controllers\Auth\LoginController;
use SanderCokart\LaravelApiAuth\Controllers\Auth\PasswordForgotController;
use SanderCokart\LaravelApiAuth\Controllers\Auth\PasswordResetController;
use SanderCokart\LaravelApiAuth\Controllers\Auth\RegisterController;

//Macro alternative: Route::ApiAuthGuestRoutes();
Route::group(['prefix' => 'account', 'as' => 'account.', 'middleware' => 'guest'], function () {
    Route::post('/register', RegisterController::class)->name('register');
    Route::post('/login', LoginController::class)->name('login');

    //VIEW PLACEHOLDER
    //Route::get('/login', fn() => view('login'))->name('login.view');
    //Route::get('/register', fn() => view('register'))->name('register.view');

    Route::group(['prefix' => 'email', 'as' => 'email.'], function () {
        Route::patch('/reset', EmailResetController::class)->name('reset');

        //VIEW PLACEHOLDER
        //Route::get('/reset', fn() => view('email-reset'))->name('reset.view');
    });

    Route::group(['prefix' => 'password', 'as' => 'password.'], function () {
        Route::post('/forgot', PasswordForgotController::class)->name('forgot');
        Route::patch('/reset', PasswordResetController::class)->name('reset');

        //VIEW PLACEHOLDER
        //Route::get('/reset', fn() => view('password-reset'))->name('reset.view');
        //Route::get('/forgot', fn() => view('password-forgot'))->name('forgot.view');
    });
});
