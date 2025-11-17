<?php

namespace Laravilt\Support\Contracts;

/**
 * Buildable Contract
 *
 * Defines components that can be instantiated via make() factory pattern.
 */
interface Buildable
{
    /**
     * Create a new component instance using the factory pattern.
     *
     * @param  string  $name  The unique name/identifier for this component
     */
    public static function make(string $name): static;
}
