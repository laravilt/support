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
     * Current operation (create, edit, view).
     */
    protected ?string $evaluationOperation = null;

    /**
     * Set the evaluation context for closures.
     */
    public function evaluationContext(array $data = [], mixed $record = null, ?string $operation = null): static
    {
        $this->evaluationData = $data;
        $this->evaluationRecord = $record;
        $this->evaluationOperation = $operation;

        return $this;
    }

    /**
     * Set the current operation.
     */
    public function operation(?string $operation): static
    {
        $this->evaluationOperation = $operation;

        return $this;
    }

    /**
     * Get the current operation.
     */
    public function getOperation(): ?string
    {
        return $this->evaluationOperation;
    }

    /**
     * Get the evaluation record (used by closures for record access).
     */
    public function getEvaluationRecord(): mixed
    {
        return $this->evaluationRecord;
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
                    } elseif ($typeName === Closure::class) {
                        // Closures cannot be resolved by the container
                        // But if the name is $get or $set, use our utilities (they're callable)
                        if ($paramName === 'get') {
                            $closureParams[] = $get;
                        } elseif ($paramName === 'set') {
                            $closureParams[] = $set;
                        } elseif ($param->isDefaultValueAvailable()) {
                            $closureParams[] = $param->getDefaultValue();
                        } else {
                            $closureParams[] = null;
                        }
                    } else {
                        // Let Laravel resolve other types
                        try {
                            $closureParams[] = app()->make($typeName);
                        } catch (\Throwable $e) {
                            // If container cannot resolve, use default or null
                            if ($param->isDefaultValueAvailable()) {
                                $closureParams[] = $param->getDefaultValue();
                            } else {
                                $closureParams[] = null;
                            }
                        }
                    }
                } elseif ($paramName === 'get') {
                    // Name-based injection for $get parameter (Filament-style)
                    $closureParams[] = $get;
                } elseif ($paramName === 'set') {
                    // Name-based injection for $set parameter (Filament-style)
                    $closureParams[] = $set;
                } elseif ($paramName === 'data') {
                    $closureParams[] = $this->evaluationData;
                } elseif ($paramName === 'record') {
                    $closureParams[] = $this->evaluationRecord;
                } elseif ($paramName === 'operation') {
                    $closureParams[] = $this->evaluationOperation;
                } elseif ($paramName === 'state') {
                    // Get the current state/value of the field
                    $closureParams[] = method_exists($this, 'getState') ? $this->getState() : null;
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
