<?php

namespace Laravilt\Support\Concerns;

use Closure;
use Laravilt\Support\Utilities\Get;
use Laravilt\Support\Utilities\Set;
use ReflectionFunction;

/**
 * EvaluatesClosures Trait
 *
 * Provides ability to evaluate closures or return direct values with dependency injection support.
 */
trait EvaluatesClosures
{
    /**
     * Evaluation context data for closures.
     */
    protected array $evaluationData = [];

    /**
     * Evaluation context record for closures.
     */
    protected mixed $evaluationRecord = null;

    /**
     * Set the evaluation context for closures.
     */
    public function evaluationContext(array $data = [], mixed $record = null): static
    {
        $this->evaluationData = $data;
        $this->evaluationRecord = $record;

        return $this;
    }

    /**
     * Evaluate a value (closure or direct value) with dependency injection support.
     *
     * @param  mixed  $value  The value to evaluate
     * @param  array  $parameters  Parameters to pass if value is a closure
     */
    protected function evaluate(mixed $value, array $parameters = []): mixed
    {
        if ($value instanceof Closure) {
            // Use reflection to get closure parameters
            $reflection = new ReflectionFunction($value);
            $closureParams = [];

            // Create Get and Set instances with references
            $get = new Get($this->evaluationData);
            $set = new Set($this->evaluationData);

            // Build parameters based on what the closure expects
            foreach ($reflection->getParameters() as $param) {
                $paramName = $param->getName();
                $paramType = $param->getType();

                // Check if a custom parameter was provided
                if (isset($parameters[$paramName])) {
                    $closureParams[] = $parameters[$paramName];

                    continue;
                }

                // Type-based injection
                if ($paramType && ! $paramType->isBuiltin()) {
                    $typeName = $paramType->getName();

                    if ($typeName === Get::class) {
                        $closureParams[] = $get;
                    } elseif ($typeName === Set::class) {
                        $closureParams[] = $set;
                    } else {
                        // Let Laravel resolve other types
                        $closureParams[] = app()->make($typeName);
                    }
                } elseif ($paramName === 'data') {
                    $closureParams[] = $this->evaluationData;
                } elseif ($paramName === 'record') {
                    $closureParams[] = $this->evaluationRecord;
                } elseif ($param->isDefaultValueAvailable()) {
                    $closureParams[] = $param->getDefaultValue();
                } else {
                    $closureParams[] = null;
                }
            }

            return $value(...$closureParams);
        }

        return $value;
    }
}
