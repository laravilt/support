<?php

namespace Laravilt\Support\Concerns;

use Closure;

/**
 * HasId Trait
 *
 * Provides custom ID functionality for components.
 */
trait HasId
{
    protected string|Closure|null $id = null;

    /**
     * Set a custom ID for the component.
     */
    public function id(string|Closure|null $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the component's ID (custom or auto-generated).
     */
    public function getId(): string
    {
        $customId = $this->evaluate($this->id);

        if ($customId) {
            return $customId;
        }

        // Auto-generate ID from name if available
        if (isset($this->name)) {
            return 'laravilt-'.$this->name.'-'.md5($this->name.spl_object_id($this));
        }

        return 'laravilt-'.md5(spl_object_id($this));
    }
}
