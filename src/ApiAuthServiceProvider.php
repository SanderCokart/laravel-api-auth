<?php

namespace SanderCokart\LaravelApiAuth;

use Carbon\CarbonInterface;
use Illuminate\Routing\Router;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use SanderCokart\LaravelApiAuth\Middleware\RootUrlMiddleware;
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
            $this->commands([
                Commands\InstallApiAuthCommand::class,
            ]);
            $this->publishEverything();
        } else {
            $this->registerCarbonMacros();
            $this->registerRouteMacros();
            $this->registerRouteMiddleware();
        }

    }

    public function publishRoutes(): void
    {
        $this->publishes([
            __DIR__ . '/routes/authenticated.php' => base_path('routes/vendor/SanderCokart/LaravelApiAuth/authenticated.php'),
            __DIR__ . '/routes/guest.php'         => base_path('routes/vendor/SanderCokart/LaravelApiAuth/guest.php'),
            __DIR__ . '/routes/verified.php'      => base_path('routes/vendor/SanderCokart/LaravelApiAuth/verified.php'),
        ], ['sc-auth-routes']);
    }

    public function publishRequests(): void
    {
        $this->publishes([
            __DIR__ . '/stubs/Requests/EmailChangeRequest.stub'       => app_path('Http/Requests/vendor/SanderCokart/LaravelApiAuth/EmailChangeRequest.php'),
            __DIR__ . '/stubs/Requests/EmailVerificationRequest.stub' => app_path('Http/Requests/vendor/SanderCokart/LaravelApiAuth/EmailVerificationRequest.php'),
            __DIR__ . '/stubs/Requests/PasswordChangeRequest.stub'    => app_path('Http/Requests/vendor/SanderCokart/LaravelApiAuth/PasswordChangeRequest.php'),
            __DIR__ . '/stubs/Requests/PasswordForgotRequest.stub'    => app_path('Http/Requests/vendor/SanderCokart/LaravelApiAuth/PasswordForgotRequest.php'),
            __DIR__ . '/stubs/Requests/PasswordResetRequest.stub'     => app_path('Http/Requests/vendor/SanderCokart/LaravelApiAuth/PasswordResetRequest.php'),
            __DIR__ . '/stubs/Requests/RegisterRequest.stub'          => app_path('Http/Requests/vendor/SanderCokart/LaravelApiAuth/RegisterRequest.php'),
            __DIR__ . '/stubs/Requests/LoginRequest.stub'             => app_path('Http/Requests/vendor/SanderCokart/LaravelApiAuth/LoginRequest.php'),
        ], ['sc-auth-requests']);
    }

    public function publishObservers(): void
    {
        $this->publishes([
            __DIR__ . '/stubs/Observers/UserObserver.stub' => app_path('Observers/UserObserver.php'),
        ], ['sc-auth-observers']);
    }

    public function publishControllers(): void
    {
        $this->publishes([
            __DIR__ . '/stubs/Controllers/EmailChangeController.stub'       => app_path('Http/Controllers/vendor/SanderCokart/LaravelApiAuth/EmailChangeController.php'),
            __DIR__ . '/stubs/Controllers/EmailVerificationController.stub' => app_path('Http/Controllers/vendor/SanderCokart/LaravelApiAuth/EmailVerificationController.php'),
            __DIR__ . '/stubs/Controllers/EmailResetController.stub'        => app_path('Http/Controllers/vendor/SanderCokart/LaravelApiAuth/EmailResetController.php'),

            __DIR__ . '/stubs/Controllers/PasswordChangeController.stub' => app_path('Http/Controllers/vendor/SanderCokart/LaravelApiAuth/PasswordChangeController.php'),
            __DIR__ . '/stubs/Controllers/PasswordForgotController.stub' => app_path('Http/Controllers/vendor/SanderCokart/LaravelApiAuth/PasswordForgotController.php'),
            __DIR__ . '/stubs/Controllers/PasswordResetController.stub'  => app_path('Http/Controllers/vendor/SanderCokart/LaravelApiAuth/PasswordResetController.php'),

            __DIR__ . '/stubs/Controllers/RegisterController.stub' => app_path('Http/Controllers/vendor/SanderCokart/LaravelApiAuth/RegisterController.php'),
            __DIR__ . '/stubs/Controllers/LoginController.stub'    => app_path('Http/Controllers/vendor/SanderCokart/LaravelApiAuth/LoginController.php'),
            __DIR__ . '/stubs/Controllers/LogoutController.stub'   => app_path('Http/Controllers/vendor/SanderCokart/LaravelApiAuth/LogoutController.php'),
        ], ['sc-auth-controllers']);
    }

    public function publishNotifications(): void
    {
        $this->publishes([
            __DIR__ . '/stubs/Notifications/EmailChangedNotification.stub'      => app_path('Notifications/vendor/SanderCokart/LaravelApiAuth/EmailChangedNotification.php'),
            __DIR__ . '/stubs/Notifications/EmailVerificationNotification.stub' => app_path('Notifications/vendor/SanderCokart/LaravelApiAuth/EmailVerificationNotification.php'),
            __DIR__ . '/stubs/Notifications/PasswordChangedNotification.stub'   => app_path('Notifications/vendor/SanderCokart/LaravelApiAuth/PasswordChangedNotification.php'),
            __DIR__ . '/stubs/Notifications/PasswordResetNotification.stub'     => app_path('Notifications/vendor/SanderCokart/LaravelApiAuth/PasswordResetNotification.php'),
        ], ['sc-auth-notifications']);
    }

    public function publishModels(): void
    {
        $this->publishes([
            __DIR__ . '/stubs/Models/EmailChange.stub'       => app_path('Models/vendor/SanderCokart/LaravelApiAuth/EmailChange.php'),
            __DIR__ . '/stubs/Models/EmailVerification.stub' => app_path('Models/vendor/SanderCokart/LaravelApiAuth/EmailVerification.php'),
            __DIR__ . '/stubs/Models/PasswordChange.stub'    => app_path('Models/vendor/SanderCokart/LaravelApiAuth/PasswordChange.php'),
            __DIR__ . '/stubs/Models/PasswordReset.stub'     => app_path('Models/vendor/SanderCokart/LaravelApiAuth/PasswordReset.php'),
        ], ['sc-auth-models']);
    }

    public function publishTheExampleUser(): void
    {
        $this->publishes([
            __DIR__ . '/stubs/Models/ExampleUser.stub' => app_path('Models/vendor/SanderCokart/LaravelApiAuth/ExampleUser.php'),
        ], ['sc-auth-example-user']);
    }

    public function publishTimezoneMigration(): void
    {
        $this->publishes([
            __DIR__ . '/migrations/add_timezone_to_users_table.php' => database_path('migrations/' . date('Y_m_d_His') . '_add_timezone_to_users_table.php'),
        ], ['sc-auth-timezone-migration']);
    }

    public function publishMigration(): void
    {
        $this->publishes([
            __DIR__ . '/migrations/create_email_and_password_related_tables.php' => database_path('migrations/' . date('Y_m_d_His') . '_create_api_auth_tables.php'),
        ], ['sc-auth-migrations']);
    }

    public function publishConfigFile(): void
    {
        $this->publishes([
            __DIR__ . '/config/api-auth.php' => config_path('api-auth.php'),
        ], ['sc-auth-config']);
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

    private function publishEverything(): void
    {
        $this->publishConfigFile();
        $this->publishControllers();
        $this->publishModels();
        $this->publishNotifications();
        $this->publishObservers();
        $this->publishRequests();
        $this->publishRoutes();
        $this->publishTheExampleUser();
        $this->publishTimezoneMigration();
        $this->publishMigration();
    }

    private function registerRouteMiddleware(): void
    {
        app(Router::class)->aliasMiddleware('api-auth.force-root-url-origin', RootUrlMiddleware::class);
    }
}
