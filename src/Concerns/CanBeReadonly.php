<?php

namespace Laravilt\Support\Concerns;

use Closure;

/**
 * CanBeReadonly Trait
 *
 * Provides readonly state functionality for components.
 */
trait CanBeReadonly
{
    protected bool|Closure $readonly = false;

    /**
     * Make the component readonly conditionally.
     */
    public function readonly(bool|Closure $condition = true): static
    {
        $this->readonly = $condition;

        return $this;
    }

    /**
     * Check if component is readonly.
     */
    public function isReadonly(): bool
    {
        return $this->evaluate($this->readonly);
    }
}
