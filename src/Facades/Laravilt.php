<?php

namespace Laravilt\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Laravilt\Auth\AuthManager;

/**
 * Laravilt Facade
 *
 * Main facade for accessing Laravilt services.
 *
 * @method static AuthManager auth()
 * @method static mixed component(string $name, array $data)
 * @method static string generateKey()
 * @method static bool isLaraviltRequest()
 * @method static bool wantsJson()
 *
 * @see \Laravilt\Support\LaraviltCore\LaraviltCore
 */
class Laravilt extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'laravilt';
    }

    /**
     * Get the AuthManager instance.
     */
    public static function auth(): AuthManager
    {
        return app('laravilt.auth');
    }
}
