<?php

namespace SanderCokart\LaravelApiAuth;

use Carbon\CarbonInterface;
use Illuminate\Routing\Router;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use SanderCokart\LaravelApiAuth\Middleware\RootUrlMiddleware;
use SanderCokart\LaravelApiAuth\Observers\UserObserver;
use SanderCokart\LaravelApiAuth\Support\SecurityToken;

class ApiAuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('security.token', function () {
            return new SecurityToken();
        });
    }

    public function boot(): void
    {
        if (app()->runningInConsole()) {
            $this->registerCarbonMacros();
            $this->registerRouteMacros();
            $this->setupPublishing();
            $this->installMiddleware();
        }
    }

    public function publishRoutes(): void
    {
        $this->publishes([
            __DIR__ . '/routes/authenticated.php' => base_path('routes/vendor/authenticated.php'),
            __DIR__ . '/routes/guest.php'         => base_path('routes/vendor/guest.php'),
        ], ['sander-cokart-auth-routes']);
    }

    public function publishRequests(): void
    {
        $this->publishes([
            __DIR__ . '/stubs/Requests/EmailChangeRequest.stub'       => app_path('Http/Requests/EmailChangeRequest.php'),
            __DIR__ . '/stubs/Requests/EmailVerificationRequest.stub' => app_path('Http/Requests/EmailVerificationRequest.php'),
            __DIR__ . '/stubs/Requests/PasswordChangeRequest.stub'    => app_path('Http/Requests/PasswordChangeRequest.php'),
            __DIR__ . '/stubs/Requests/PasswordForgotRequest.stub'    => app_path('Http/Requests/PasswordForgotRequest.php'),
            __DIR__ . '/stubs/Requests/PasswordResetRequest.stub'     => app_path('Http/Requests/PasswordResetRequest.php'),
            __DIR__ . '/stubs/Requests/RegisterRequest.stub'          => app_path('Http/Requests/RegisterRequest.php'),
            __DIR__ . '/stubs/Requests/LoginRequest.stub'             => app_path('Http/Requests/LoginRequest.php'),
        ], ['sander-cokart-auth-requests']);
    }

    public function publishObservers(): void
    {
        $this->publishes([
            __DIR__ . '/stubs/Observers/UserObserver.stub' => app_path('Observers/UserObserver.php'),
        ], ['sander-cokart-auth-observers']);
    }

    public function publishControllers(): void
    {
        $this->publishes([
            __DIR__ . '/stubs/Controllers/Auth/EmailChangeController.stub'       => app_path('Http/Controllers/Auth/EmailChangeController.php'),
            __DIR__ . '/stubs/Controllers/Auth/EmailVerificationController.stub' => app_path('Http/Controllers/Auth/EmailVerificationController.php'),
            __DIR__ . '/stubs/Controllers/Auth/EmailResetController.stub'        => app_path('Http/Controllers/Auth/EmailResetController.php'),

            __DIR__ . '/stubs/Controllers/Auth/PasswordChangeController.stub' => app_path('Http/Controllers/Auth/PasswordChangeController.php'),
            __DIR__ . '/stubs/Controllers/Auth/PasswordForgotController.stub' => app_path('Http/Controllers/Auth/PasswordForgotController.php'),
            __DIR__ . '/stubs/Controllers/Auth/PasswordResetController.stub'  => app_path('Http/Controllers/Auth/PasswordResetController.php'),

            __DIR__ . '/stubs/Controllers/Auth/RegisterController.stub' => app_path('Http/Controllers/Auth/RegisterController.php'),
            __DIR__ . '/stubs/Controllers/Auth/LoginController.stub'    => app_path('Http/Controllers/Auth/LoginController.php'),
            __DIR__ . '/stubs/Controllers/Auth/LogoutController.stub'   => app_path('Http/Controllers/Auth/LogoutController.php'),
        ], ['sander-cokart-auth-controllers']);
    }

    public function publishNotifications(): void
    {
        $this->publishes([
            __DIR__ . '/stubs/Notifications/EmailChangedNotification.stub'      => app_path('Notifications/vendor/SanderCokart/EmailChangeNotification.php'),
            __DIR__ . '/stubs/Notifications/EmailVerificationNotification.stub' => app_path('Notifications/vendor/SanderCokart/EmailVerificationNotification.php'),
            __DIR__ . '/stubs/Notifications/PasswordChangedNotification.stub'   => app_path('Notifications/vendor/SanderCokart/PasswordChangeNotification.php'),
            __DIR__ . '/stubs/Notifications/PasswordResetNotification.stub'     => app_path('Notifications/vendor/SanderCokart/PasswordResetNotification.php'),
        ], ['sander-cokart-auth-notifications']);
    }

    public function publishModels(): void
    {
        $this->publishes([
            __DIR__ . '/stubs/Models/EmailChange.stub'       => app_path('Models/EmailChange.php'),
            __DIR__ . '/stubs/Models/EmailVerification.stub' => app_path('Models/EmailVerification.php'),
            __DIR__ . '/stubs/Models/PasswordChange.stub'    => app_path('Models/PasswordChange.php'),
            __DIR__ . '/stubs/Models/PasswordReset.stub'     => app_path('Models/PasswordReset.php'),
        ], ['auth-models']);
    }

    public function publishTheExampleUser(): void
    {
        $this->publishes([
            __DIR__ . '/stubs/Models/ExampleUser.stub' => app_path('Models/ExampleUser.php'),
        ], ['sander-cokart-auth-example-user']);
    }

    public function publishTheOptionalMigration(): void
    {
        $this->publishes([
            __DIR__ . '/migrations/2023_02_05_154339_add_timezone_to_users_table.php' => database_path('migrations/' . date('Y_m_d_His', time()) . '_add_timezone_to_users_table.php'),
        ], ['sander-cokart-auth-migrations']);
    }

    public function publishConfigFile(): void
    {
        $this->publishes([
            __DIR__ . '/config/api-auth.php' => config_path('api-auth.php'),
        ], ['sander-cokart-auth-config']);
    }

    private function registerCarbonMacros(): void
    {
        Carbon::macro('toExpirationDate', static function (int $minutes) {
            return self::this()->addMinutes($minutes)->format('l \t\h\e jS \o\f F Y \a\t g:i A');
        });

        Carbon::macro('toTimeRemaining', static function (int $minutes) {
            return self::this()->diffForHumans(
                Carbon::now()->addMinutes($minutes), CarbonInterface::DIFF_ABSOLUTE
            );
        });
    }

    private function registerRouteMacros(): void
    {
        Route::macro('ApiAuthGuestRoutes', function () {
            Route::post('/register', config('api-auth.routes.guest-routes.register'))->name('register');
            Route::post('/login', config('api-auth.routes.guest-routes.login'))->name('login');

            Route::group(['prefix' => 'email', 'as' => 'email.'], function () {
                Route::patch('/reset', config('api-auth.routes.guest-routes.email.reset'))->name('reset');
            });

            Route::group(['prefix' => 'password', 'as' => 'password.'], function () {
                Route::post('/forgot', config('api-auth.routes.guest-routes.password.forgot'))->name('forgot');
                Route::patch('/reset', config('api-auth.routes.guest-routes.password.reset'))->name('reset');
            });
        });

        Route::macro('ApiAuthAuthenticatedRoutes', function () {
            Route::post('/logout', config('api-auth.routes.authenticated-routes.logout'))->name('logout');

            Route::group(['prefix' => 'email', 'as' => 'email.'], function () {
                Route::post('/verify', config('api-auth.routes.authenticated-routes.email.verify'))->name('verify');
                Route::patch('/change', config('api-auth.routes.authenticated-routes.email.change'))->name('change');
            });

            Route::group(['prefix' => 'password', 'as' => 'password.'], function () {
                Route::patch('/change', config('api-auth.routes.authenticated-routes.password.change'))->name('change');
            });
        });
    }

    private function setupPublishing(): void
    {
        $this->publishConfigFile();
        $this->publishControllers();
        $this->publishModels();
        $this->publishNotifications();
        $this->publishObservers();
        $this->publishRequests();
        $this->publishRoutes();
        $this->publishTheExampleUser();
        $this->publishTheOptionalMigration();
    }

    private function installMiddleware(): void
    {
        app(Router::class)->aliasMiddleware('api.auth.force.root.url.origin', RootUrlMiddleware::class);
    }
}
