<?php

use Laravilt\Support\SupportServiceProvider;
use Orchestra\Testbench\TestCase;

uses(TestCase::class)->in('Feature', 'Unit');

// Boot Laravel application
function getPackageProviders($app)
{
    return [SupportServiceProvider::class];
}
