<?php

namespace Laravilt\Support\Concerns;

use Closure;

/**
 * EvaluatesClosures Trait
 *
 * Provides ability to evaluate closures or return direct values.
 */
trait EvaluatesClosures
{
    /**
     * Evaluate a value (closure or direct value).
     *
     * @param  mixed  $value  The value to evaluate
     * @param  array  $parameters  Parameters to pass if value is a closure
     */
    protected function evaluate(mixed $value, array $parameters = []): mixed
    {
        if ($value instanceof Closure) {
            return $value(...$parameters);
        }

        return $value;
    }
}
