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
    protected bool|Closure|null $hidden = null;

    protected bool|Closure|null $visible = null;

    /**
     * Hide the component conditionally.
     */
    public function hidden(bool|Closure $condition = true): static
    {
        $this->hidden = $condition;
        $this->visible = null; // Reset visible when hidden is set

        return $this;
    }

    /**
     * Show the component conditionally.
     */
    public function visible(bool|Closure $condition = true): static
    {
        $this->visible = $condition;
        $this->hidden = null; // Reset hidden when visible is set

        return $this;
    }

    /**
     * Check if component is hidden.
     */
    public function isHidden(): bool
    {
        // If hidden is explicitly set, evaluate it
        if ($this->hidden !== null) {
            return $this->evaluate($this->hidden) === true;
        }

        // If visible is explicitly set, return opposite
        if ($this->visible !== null) {
            return $this->evaluate($this->visible) === false;
        }

        // Default: not hidden
        return false;
    }

    /**
     * Check if component is visible.
     */
    public function isVisible(): bool
    {
        return ! $this->isHidden();
    }
}
