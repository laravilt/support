<?php

namespace Laravilt\Support\Concerns;

use Closure;

/**
 * CanBeDisabled Trait
 *
 * Provides disabled state functionality for components.
 */
trait CanBeDisabled
{
    protected bool|Closure $disabled = false;

    /**
     * Disable the component conditionally.
     */
    public function disabled(bool|Closure $condition = true): static
    {
        $this->disabled = $condition;

        return $this;
    }

    /**
     * Check if component is disabled.
     */
    public function isDisabled(): bool
    {
        return $this->evaluate($this->disabled);
    }

    /**
     * Check if component is enabled.
     */
    public function isEnabled(): bool
    {
        return ! $this->isDisabled();
    }
}
