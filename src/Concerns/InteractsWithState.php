<?php

namespace Laravilt\Support\Concerns;

/**
 * InteractsWithState Trait
 *
 * Provides state management functionality for components.
 */
trait InteractsWithState
{
    public mixed $state = null;

    protected mixed $defaultState = null;

    /**
     * Set the component's state/value.
     */
    public function state(mixed $state): static
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Set the default state.
     */
    public function default(mixed $state): static
    {
        $this->defaultState = $state;

        return $this;
    }

    /**
     * Get the component's current state.
     */
    public function getState(): mixed
    {
        return $this->state ?? $this->defaultState;
    }

    /**
     * Check if component has state.
     */
    public function hasState(): bool
    {
        return $this->state !== null;
    }
}
