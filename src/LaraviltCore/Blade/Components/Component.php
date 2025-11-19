<?php

namespace Laravilt\Support\LaraviltCore\Blade\Components;

use Illuminate\View\Component as BladeComponent;

/**
 * Laravilt Blade Component
 *
 * Renders a Laravilt component in Blade templates.
 * Usage: <x-laravilt-component name="my-component" :data="$data" />
 */
class Component extends BladeComponent
{
    public function __construct(
        public string $name,
        public array $data = []
    ) {}

    public function render()
    {
        return view('laravilt-support::laravilt-core.components.component', [
            'name' => $this->name,
            'data' => $this->data,
        ]);
    }
}
