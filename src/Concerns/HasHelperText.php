<?php

namespace Laravilt\Support\Concerns;

use Closure;

/**
 * HasHelperText Trait
 *
 * Provides helper/hint text functionality for components.
 */
trait HasHelperText
{
    protected string|Closure|null $helperText = null;

    protected string|Closure|null $hint = null;

    /**
     * Set helper text for the component.
     */
    public function helperText(string|Closure $text): static
    {
        $this->helperText = $text;

        return $this;
    }

    /**
     * Set hint text (alias for helperText).
     */
    public function hint(string|Closure $text): static
    {
        $this->hint = $text;

        return $this;
    }

    /**
     * Get the helper text.
     */
    public function getHelperText(): ?string
    {
        return $this->evaluate($this->helperText ?? $this->hint);
    }
}
