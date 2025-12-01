<?php

namespace Laravilt\Support\Concerns;

use Closure;

/**
 * HasLabel Trait
 *
 * Provides label functionality to components.
 */
trait HasLabel
{
    protected string|Closure|null $label = null;

    /**
     * Set the component's label.
     */
    public function label(string|Closure $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get the component's label (evaluates closures).
     * If no label is set, generates a default label from the field name.
     */
    public function getLabel(): ?string
    {
        $label = $this->evaluate($this->label);

        // If no label is set, generate one from the field name
        if ($label === null && isset($this->name)) {
            return $this->generateLabelFromName($this->name);
        }

        return $label;
    }

    /**
     * Generate a human-readable label from a field name.
     *
     * Examples:
     * - 'first_name' => 'First Name'
     * - 'email_address' => 'Email Address'
     * - 'company' => 'Company'
     */
    protected function generateLabelFromName(string $name): string
    {
        // Remove common prefixes/suffixes
        $name = preg_replace('/^_+|_+$/', '', $name);

        // Replace underscores and hyphens with spaces
        $name = str_replace(['_', '-'], ' ', $name);

        // Convert to title case
        return \Illuminate\Support\Str::title($name);
    }
}
