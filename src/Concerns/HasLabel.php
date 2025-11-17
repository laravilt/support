<?php

namespace Laravilt\Support\Concerns;

use Closure;

/**
 * HasLabel Trait
 *
 * Provides label functionality to components.
 */
trait HasLabel
{
    protected string|Closure|null $label = null;

    /**
     * Set the component's label.
     */
    public function label(string|Closure $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get the component's label (evaluates closures).
     */
    public function getLabel(): ?string
    {
        return $this->evaluate($this->label);
    }
}
