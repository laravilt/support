<?php

namespace Laravilt\Support\Utilities;

/**
 * Get Utility
 *
 * Provides safe access to nested array values using dot notation.
 */
class Get
{
    /**
     * Get a value from an array using dot notation.
     *
     * @param  array  $data  The data array
     * @param  string  $key  The key in dot notation (e.g., 'user.name')
     * @param  mixed  $default  Default value if key doesn't exist
     */
    public static function value(array $data, string $key, mixed $default = null): mixed
    {
        // Direct key exists
        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        // Not a nested key
        if (! str_contains($key, '.')) {
            return $default;
        }

        // Navigate through nested structure
        foreach (explode('.', $key) as $segment) {
            if (is_array($data) && array_key_exists($segment, $data)) {
                $data = $data[$segment];
            } else {
                return $default;
            }
        }

        return $data;
    }
}
