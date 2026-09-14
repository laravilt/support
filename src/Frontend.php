<?php

namespace Laravilt\Support;

/**
 * Resolves which frontend stack (Vue or React) the host application uses.
 *
 * Order: the `laravilt-support.frontend` config value (LARAVILT_FRONTEND),
 * then detection from the application's package.json, then Vue.
 */
class Frontend
{
    public const string VUE = 'vue';

    public const string REACT = 'react';

    public const array STACKS = [self::VUE, self::REACT];

    public static function stack(): string
    {
        $configured = config('laravilt-support.frontend');

        if (is_string($configured) && static::isValid($configured)) {
            return strtolower($configured);
        }

        return static::detect();
    }

    public static function detect(?string $packageJsonPath = null): string
    {
        $path = $packageJsonPath ?? base_path('package.json');

        if (! is_file($path)) {
            return self::VUE;
        }

        $json = json_decode((string) file_get_contents($path), true);

        if (! is_array($json)) {
            return self::VUE;
        }

        $dependencies = array_merge($json['dependencies'] ?? [], $json['devDependencies'] ?? []);

        $usesReact = isset($dependencies['react']) || isset($dependencies['@inertiajs/react']);
        $usesVue = isset($dependencies['vue']) || isset($dependencies['@inertiajs/vue3']);

        return $usesReact && ! $usesVue ? self::REACT : self::VUE;
    }

    public static function isValid(string $stack): bool
    {
        return in_array(strtolower($stack), self::STACKS, true);
    }

    public static function isReact(): bool
    {
        return static::stack() === self::REACT;
    }

    public static function isVue(): bool
    {
        return static::stack() === self::VUE;
    }

    /**
     * The folder under a package's resources/ that holds the given stack's frontend source.
     */
    public static function resourceDirectory(?string $stack = null): string
    {
        return ($stack ?? static::stack()) === self::REACT ? 'react' : 'js';
    }
}
