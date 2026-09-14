<?php

namespace Laravilt\Support\Tests;

use Laravilt\Support\SupportServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // Additional setup if needed
    }

    protected function getPackageProviders($app): array
    {
        return [
            SupportServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        // Setup environment for testing
        config()->set('database.default', 'testing');

        // Register test views path
        $app['view']->addLocation(__DIR__.'/views');
    }
}
