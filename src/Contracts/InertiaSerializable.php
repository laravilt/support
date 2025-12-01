<?php

namespace Laravilt\Support\Contracts;

interface InertiaSerializable
{
    /**
     * Serialize the component to Inertia props for Vue.
     *
     * @return array<string, mixed>
     */
    public function toInertiaProps(): array;
}
