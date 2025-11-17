<?php

use Laravilt\Support\SupportPlugin;
use Orchestra\Testbench\TestCase;

uses(TestCase::class)->in('Feature', 'Unit');

// Boot Laravel application
function getPackageProviders($app)
{
    return [SupportPlugin::class];
}
