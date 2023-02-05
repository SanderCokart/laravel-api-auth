<?php

use Illuminate\Support\Facades\Route;
use SanderCokart\LaravelApiAuth\Controllers\Auth\EmailChangeController;
use SanderCokart\LaravelApiAuth\Controllers\Auth\EmailVerificationController;
use SanderCokart\LaravelApiAuth\Controllers\Auth\LogoutController;
use SanderCokart\LaravelApiAuth\Controllers\Auth\PasswordChangeController;

Route::group(['prefix' => 'account', 'as' => 'account.', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', LogoutController::class)->name('logout');

    Route::group(['prefix' => 'email', 'as' => 'email.'], function () {
        Route::post('/verify', EmailVerificationController::class)->name('verify');
        Route::patch('/change', EmailChangeController::class)->name('change');
    });

    Route::group(['prefix' => 'password', 'as' => 'password.'], function () {
        Route::patch('/change', PasswordChangeController::class)->name('change');
    });
});
