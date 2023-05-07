<?php

use Illuminate\Support\Facades\Route;
use SanderCokart\LaravelApiAuth\Controllers\{
    EmailVerificationController,
    LogoutController
};

//Macro alternative: Route::ApiAuthAuthenticatedRoutes(); // Does NOT include GET routes for views
Route::group(['prefix' => 'account', 'as' => 'account.', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', LogoutController::class)->name('logout');

    Route::group(['prefix' => 'email', 'as' => 'email.', 'middleware' => 'api-auth.force-root-url-origin'], function () {
        Route::post('/verify', EmailVerificationController::class)->name('verify');

        //VIEW PLACEHOLDER
        //Route::get('/verify', fn() => view('email.verify'))->name('verify.view');
    });
});
