<?php

namespace Laravilt\Support;

use Illuminate\Support\ServiceProvider;
use Laravilt\Support\Commands\MakeComponentCommand;
use Laravilt\Support\LaraviltCore\LaraviltServiceProvider;

/**
 * Support Service Provider
 *
 * Foundation service provider for Laravilt framework.
 * Registers base Component class, Laravilt Core, and utilities.
 */
class SupportServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register Laravilt Core
        $this->app->register(LaraviltServiceProvider::class);

        // Merge config
        $this->mergeConfigFrom(
            __DIR__.'/../config/laravilt-support.php',
            'laravilt-support'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravilt-support');

        // Load translations
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravilt-support');

        if ($this->app->runningInConsole()) {
            // Register commands
            $this->commands([
                MakeComponentCommand::class,
            ]);

            // Publish config
            $this->publishes([
                __DIR__.'/../config/laravilt-support.php' => config_path('laravilt-support.php'),
            ], 'laravilt-support-config');

            // Publish assets
            $this->publishes([
                __DIR__.'/../dist' => public_path('vendor/laravilt/support'),
            ], 'laravilt-support-assets');

            // Publish views
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravilt/support'),
            ], 'laravilt-support-views');

            // Publish translations
            $this->publishes([
                __DIR__.'/../resources/lang' => lang_path('vendor/laravilt/support'),
            ], 'laravilt-support-translations');
        }
    }
}
