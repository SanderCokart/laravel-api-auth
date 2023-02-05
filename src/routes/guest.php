<?php

use Illuminate\Support\Facades\Route;
use SanderCokart\LaravelApiAuth\Controllers\Auth\EmailResetController;
use SanderCokart\LaravelApiAuth\Controllers\Auth\LoginController;
use SanderCokart\LaravelApiAuth\Controllers\Auth\PasswordForgotController;
use SanderCokart\LaravelApiAuth\Controllers\Auth\PasswordResetController;
use SanderCokart\LaravelApiAuth\Controllers\Auth\RegisterController;

Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
    Route::post('/register', RegisterController::class)->name('register');
    Route::post('/login', LoginController::class)->name('login');

    Route::group(['prefix' => 'email', 'as' => 'email.'], function () {
        Route::patch('/reset', EmailResetController::class)->name('reset');
    });

    Route::group(['prefix' => 'password', 'as' => 'password.'], function () {
        Route::post('/forgot', PasswordForgotController::class)->name('forgot');
        Route::patch('/reset', PasswordResetController::class)->name('reset');
    });
});
