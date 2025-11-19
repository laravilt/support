<?php

namespace Laravilt\Support\LaraviltCore;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Laravilt\Support\LaraviltCore\Blade\Components\Component;
use Laravilt\Support\LaraviltCore\Blade\Components\Link;
use Laravilt\Support\LaraviltCore\Blade\Components\Modal;
use Laravilt\Support\LaraviltCore\Blade\Components\VueComponent;

/**
 * Laravilt Core Service Provider
 *
 * Registers Laravilt Core functionality including Blade components.
 */
class LaraviltServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register core singleton
        $this->app->singleton(LaraviltCore::class);
    }

    public function boot(): void
    {
        // Register Blade components
        Blade::component('laravilt-component', Component::class);
        Blade::component('laravilt-vue-component', VueComponent::class);
        Blade::component('laravilt-link', Link::class);
        Blade::component('laravilt-modal', Modal::class);

        // Register custom Blade directives
        $this->registerBladeDirectives();
    }

    /**
     * Register custom Blade directives.
     */
    protected function registerBladeDirectives(): void
    {
        // @laravilt directive for checking Laravilt requests
        Blade::if('laravilt', function () {
            return LaraviltCore::isLaraviltRequest();
        });
    }
}
