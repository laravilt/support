<?php

namespace Laravilt\Support\Concerns;

use Closure;

/**
 * CanBeRequired Trait
 *
 * Provides required state functionality for form fields.
 */
trait CanBeRequired
{
    protected bool|Closure $required = false;

    /**
     * Mark the field as required conditionally.
     */
    public function required(bool|Closure $condition = true): static
    {
        $this->required = $condition;

        return $this;
    }

    /**
     * Check if field is required.
     */
    public function isRequired(): bool
    {
        return $this->evaluate($this->required);
    }
}
