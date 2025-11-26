<?php

namespace Laravilt\Support\LaraviltCore\Blade\Components;

use Illuminate\View\Component as BladeComponent;

/**
 * Laravilt Link Component
 *
 * Renders an SPA-enabled link.
 * Usage: <x-laravilt-link href="/path">Link Text</x-laravilt-link>
 */
class Link extends BladeComponent
{
    public function __construct(
        public string $href,
        public ?string $method = 'GET'
    ) {}

    public function render()
    {
        return view('laravilt-support::laravilt-core.components.link');
    }
}
