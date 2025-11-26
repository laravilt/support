<?php

namespace Laravilt\Support\LaraviltCore\Blade\Components;

use Illuminate\View\Component as BladeComponent;

/**
 * Laravilt Modal Component
 *
 * Renders a modal dialog.
 * Usage: <x-laravilt-modal name="my-modal" :open="true">Content</x-laravilt-modal>
 */
class Modal extends BladeComponent
{
    public function __construct(
        public string $name,
        public bool $open = false,
        public ?string $title = null
    ) {}

    public function render()
    {
        return view('laravilt-support::laravilt-core.components.modal');
    }
}
