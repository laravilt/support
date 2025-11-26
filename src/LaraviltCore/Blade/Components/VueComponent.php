<?php

namespace Laravilt\Support\LaraviltCore\Blade\Components;

use Illuminate\View\Component as BladeComponent;

/**
 * Vue Component Blade Wrapper
 *
 * Renders a Vue component placeholder in Blade that gets hydrated on the frontend.
 * Similar to Splade's approach where Blade templates contain Vue component markers.
 *
 * Usage: <x-laravilt-vue-component component="Tabs" :props="['tabs' => $tabs]" />
 */
class VueComponent extends BladeComponent
{
    public function __construct(
        public string $component,
        public array $props = []
    ) {}

    public function render()
    {
        return view('laravilt-support::laravilt-core.components.vue-component', [
            'component' => $this->component,
            'props' => $this->props,
        ]);
    }
}
