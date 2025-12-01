<?php

namespace Laravilt\Support\Utilities;

/**
 * Set Utility
 *
 * Provides safe setting of nested array values using dot notation.
 * Can be used as a callable or static utility.
 */
class Set
{
    /**
     * The form data array.
     */
    protected array $data;

    /**
     * Track if any changes were made.
     */
    protected bool $hasChanges = false;

    /**
     * Create a new Set instance.
     */
    public function __construct(array &$data = [])
    {
        $this->data = &$data;
    }

    /**
     * Set a value in the form data using dot notation.
     *
     * @param  string  $key  The key in dot notation (e.g., 'user.name')
     * @param  mixed  $value  The value to set
     */
    public function __invoke(string $key, mixed $value): void
    {
        \Log::info('Set::__invoke called', [
            'key' => $key,
            'value' => $value,
            'data_before' => $this->data,
        ]);
        static::value($this->data, $key, $value);
        $this->hasChanges = true;
        \Log::info('Set::__invoke after', [
            'data_after' => $this->data,
        ]);
    }

    /**
     * Check if any changes were made.
     */
    public function hasChanges(): bool
    {
        return $this->hasChanges;
    }

    /**
     * Get the data array.
     */
    public function getData(): array
    {
        return $this->data;
    }

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
