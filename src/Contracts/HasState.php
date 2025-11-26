<?php

namespace Laravilt\Support\Contracts;

/**
 * HasState Contract
 *
 * Defines components that maintain internal state.
 */
interface HasState
{
    /**
     * Set the component's state/value.
     */
    public function state(mixed $state): static;

    /**
     * Get the component's current state/value.
     */
    public function getState(): mixed;
}
