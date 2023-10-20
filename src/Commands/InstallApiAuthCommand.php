<?php

namespace SanderCokart\LaravelApiAuth\Commands;

use Illuminate\Console\Command;

class InstallApiAuthCommand extends Command
{
    protected $signature = 'api-auth:install';

    protected $description = 'Installs the Laravel Api Auth package';

    public function handle(): void
    {
        system('clear || cls');
        sleep(1);

        //TODO fix readme

        $this->alert('Laravel Api Auth installer!');
        $this->info('All published files will be placed in a retrospective vendor/SanderCokart/LaravelApiAuth folder. Except for the config file which will be placed in config/api-auth.php');

        //UL list of all the things that will be published e.g: Models, Controllers, Notifications, Migrations, etc.
        $this->line('* App/Models/vendor/SanderCokart/LaravelApiAuth');
        $this->line('* Http/Controllers/vendor/SanderCokart/LaravelApiAuth');
        $this->line('* Notifications/vendor/SanderCokart/LaravelApiAuth');
        $this->line('* Http/Requests/vendor/SanderCokart/LaravelApiAuth');
        $this->line('* Observers/vendor/SanderCokart/LaravelApiAuth');

        $this->newLine();

        $this->info('Is your app used internationally?, Saying yes will add a timezone column to the user table.');
        $this->line('We use timezone data to convert token expire times to the users timezone.');
        $this->info('https://github.com/jamesmills/laravel-timezone');
        $this->line('We also recommend using `jamesmills/laravel-timezone` package which will automatically add the users timezone upon registration.');
        $this->line('If you choose not to use `jamesmills/laravel-timezone` you\'ll have to get the users timezone yourself.');
        $this->line('There is a field for this in App\\Requests\\vendor\\SanderCokart\\LaravelApiAuth\\RegisterRequest.php');
        $this->line('In javascript you can get the users timezone with `Intl.DateTimeFormat().resolvedOptions().timeZone`');

        $enableTimezones = $this->confirm('Do you want to enable timezones?', false);
        $publishExampleUser = $this->confirm('Publish an example user model?', false);
        $salutation = $this->ask('What should your initial notification salutation be?', 'Kind regards, ' . config('app.name'));
        $this->line('You can always edit the actual Notifications and disregard the config salutation.');

        $this->call('vendor:publish', [
            '--provider' => "SanderCokart\LaravelApiAuth\ApiAuthServiceProvider",
            '--tag'      => [
                ($enableTimezones ? 'sc-auth-timezone-migration' : ''),
                ($publishExampleUser ? 'sc-auth-example-user' : ''),
                'sc-auth-controllers',
                'sc-auth-migrations',
                'sc-auth-models',
                'sc-auth-notifications',
                'sc-auth-requests',
                'sc-auth-routes',
            ],
        ]);

        $this->info('Generating config file...');
        $config = file_get_contents(__DIR__ . '/../stubs/config/api-auth.stub');

        $config = str_replace([
            ':salutation',
            ':timezones_enabled',
        ], [
            "'{$salutation}'",
            $enableTimezones ? 'true' : 'false',
        ], $config);

        file_put_contents(config_path('api-auth.php'), $config);
        $this->info('Config file generated! You can find it in config/api-auth.php');

        $this->newLine();

        $this->info('Do not forget to add the published UserObserver to your EventServiceProvider.php');
    }
}
