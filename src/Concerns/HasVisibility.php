<?php

namespace Laravilt\Support\Concerns;

use Closure;

/**
 * HasVisibility Trait
 *
 * Provides visibility control (show/hide) for components.
 */
trait HasVisibility
{
    protected bool|Closure $hidden = false;

    protected bool|Closure $visible = true;

    /**
     * Hide the component conditionally.
     */
    public function hidden(bool|Closure $condition = true): static
    {
        $this->hidden = $condition;

        return $this;
    }

    /**
     * Show the component conditionally.
     */
    public function visible(bool|Closure $condition = true): static
    {
        $this->visible = $condition;

        return $this;
    }

    /**
     * Check if component is hidden.
     */
    public function isHidden(): bool
    {
        if ($this->evaluate($this->hidden) === true) {
            return true;
        }

        return $this->evaluate($this->visible) === false;
    }

    /**
     * Check if component is visible.
     */
    public function isVisible(): bool
    {
        return ! $this->isHidden();
    }
}
