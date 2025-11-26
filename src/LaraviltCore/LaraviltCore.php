<?php

namespace Laravilt\Support\LaraviltCore;

/**
 * Laravilt Core
 *
 * Main class for Laravilt SPA functionality.
 * Handles component registration, request detection, and key generation.
 */
class LaraviltCore
{
    /**
     * Register a component with Laravilt.
     */
    public static function component(string $name, array $data): array
    {
        return [
            'laravilt' => [
                'component' => $name,
                'data' => $data,
                'key' => static::generateKey(),
            ],
        ];
    }

    /**
     * Generate unique component key.
     */
    public static function generateKey(): string
    {
        return 'laravilt-'.uniqid('', true);
    }

    /**
     * Check if current request is a Laravilt SPA request.
     */
    public static function isLaraviltRequest(): bool
    {
        return request()->header('X-Laravilt') === 'true';
    }

    /**
     * Check if current request expects JSON response.
     */
    public static function wantsJson(): bool
    {
        return static::isLaraviltRequest() || request()->wantsJson();
    }
}
