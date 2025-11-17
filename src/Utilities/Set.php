<?php

namespace Laravilt\Support\Utilities;

/**
 * Set Utility
 *
 * Provides safe setting of nested array values using dot notation.
 */
class Set
{
    /**
     * Set a value in an array using dot notation.
     *
     * @param  array  $data  The data array (passed by reference)
     * @param  string  $key  The key in dot notation (e.g., 'user.name')
     * @param  mixed  $value  The value to set
     * @return array The modified array
     */
    public static function value(array &$data, string $key, mixed $value): array
    {
        // Direct key
        if (! str_contains($key, '.')) {
            $data[$key] = $value;

            return $data;
        }

        // Navigate and create nested structure
        $keys = explode('.', $key);
        $current = &$data;

        foreach ($keys as $i => $segment) {
            // Last segment - set the value
            if ($i === count($keys) - 1) {
                $current[$segment] = $value;
                break;
            }

            // Create array if doesn't exist
            if (! isset($current[$segment]) || ! is_array($current[$segment])) {
                $current[$segment] = [];
            }

            // Move deeper
            $current = &$current[$segment];
        }

        return $data;
    }
}
