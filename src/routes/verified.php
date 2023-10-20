<?php

use SanderCokart\LaravelApiAuth\Controllers\{
    EmailChangeController,
    PasswordChangeController
};

Route::group(['prefix' => 'account', 'as' => 'account.', 'middleware' => 'auth:sanctum'], function () {
    Route::group(['prefix' => 'email', 'as' => 'email.'], function () {
        Route::patch('/change', EmailChangeController::class)->name('change');

        //VIEW PLACEHOLDER
        //Route::get('/change', fn() => view('email.change'))->name('change.view');
    });

    Route::group(['prefix' => 'password', 'as' => 'password.'], function () {
        Route::patch('/change', PasswordChangeController::class)->name('change');
        //VIEW PLACEHOLDER
        //Route::get('/change', fn() => view('password.change'))->name('change.view');
    });
});