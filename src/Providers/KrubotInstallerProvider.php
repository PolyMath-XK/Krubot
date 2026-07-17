<?php

namespace KrubiK\Providers;

use Illuminate\Support\ServiceProvider;
use KrubiK\Console\KrubotInstaller; // Command within your package

/**
 * Class KrubotInstallerProvider
 * @package KrubiK\Providers
 *
 * This Service Provider is responsible for registering Krubot's services,
 * configurations, and most importantly, its Artisan commands.
 */
class KrubotInstallerProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * This method is where you can register any Artisan commands
     * your package provides.
     *
     * @return void
     */
    public function boot(): void
    {
        // Only register commands if the application is running in console (Artisan context)
        // if ($this->app->runningInConsole()) {
            // Register the package's console commands with Laravel's Artisan.
            // This makes them discoverable when `php artisan` is run.
            $this->commands([
                KrubotInstaller::class,
            ]);
        // }
    }
}
