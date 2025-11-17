<?php

namespace Laravilt\Support\Concerns;

use Closure;

/**
 * HasColumnSpan Trait
 *
 * Provides column span functionality for grid-based layouts.
 */
trait HasColumnSpan
{
    protected int|string|Closure|null $columnSpan = null;

    /**
     * Set the column span (e.g., 1, 2, 'full').
     */
    public function columnSpan(int|string|Closure $span): static
    {
        $this->columnSpan = $span;

        return $this;
    }

    /**
     * Make component span full width.
     */
    public function columnSpanFull(): static
    {
        $this->columnSpan = 'full';

        return $this;
    }

    /**
     * Get the column span value.
     */
    public function getColumnSpan(): int|string|null
    {
        return $this->evaluate($this->columnSpan);
    }
}
