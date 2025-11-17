<?php

namespace Laravilt\Support\Contracts;

use Closure;

/**
 * HasLabel Contract
 *
 * Defines components that support labels.
 */
interface HasLabel
{
    /**
     * Set the component's label.
     */
    public function label(string|Closure $label): static;

    /**
     * Get the component's label.
     */
    public function getLabel(): ?string;
}
