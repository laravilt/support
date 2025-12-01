<?php

namespace Laravilt\Support\Utilities;

/**
 * Get Utility
 *
 * Provides safe access to nested array values using dot notation.
 * Can be used as a callable or static utility.
 */
class Get
{
    /**
     * The form data array.
     */
    protected array $data;

    /**
     * Create a new Get instance.
     */
    public function __construct(array &$data = [])
    {
        $this->data = &$data;
    }

    /**
     * Get a value from the form data using dot notation.
     *
     * @param  string  $key  The key in dot notation (e.g., 'user.name')
     * @param  mixed  $default  Default value if key doesn't exist
     */
    public function __invoke(string $key, mixed $default = null): mixed
    {
        $value = static::value($this->data, $key, $default);
        \Log::info('Get::__invoke called', [
            'key' => $key,
            'value' => $value,
            'all_data' => $this->data,
        ]);

        return $value;
    }

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
