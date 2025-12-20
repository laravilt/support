<?php

namespace Laravilt\Support\Concerns;

use Closure;

/**
 * HasColumnSpan Trait
 *
 * Provides column span functionality for grid-based layouts.
 * Supports responsive breakpoints: 'default', 'sm', 'md', 'lg', 'xl', '2xl'
 */
trait HasColumnSpan
{
    protected int|string|array|Closure|null $columnSpan = null;

    protected int|string|array|Closure|null $columnStart = null;

    /**
     * Set the column span.
     *
     * Accepts:
     * - int: Fixed column span (e.g., 2)
     * - string: 'full' for full width
     * - array: Responsive breakpoints (e.g., ['default' => 1, 'sm' => 2, 'md' => 3, 'lg' => 4])
     * - Closure: Dynamic value
     */
    public function columnSpan(int|string|array|Closure $span): static
    {
        $this->columnSpan = $span;

        return $this;
    }

    /**
     * Set the column start position.
     *
     * Accepts:
     * - int: Fixed start position (e.g., 2)
     * - string: 'auto'
     * - array: Responsive breakpoints (e.g., ['default' => 1, 'sm' => 2, 'lg' => 3])
     * - Closure: Dynamic value
     */
    public function columnStart(int|string|array|Closure $start): static
    {
        $this->columnStart = $start;

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
    public function getColumnSpan(): int|string|array|null
    {
        return $this->evaluate($this->columnSpan);
    }

    /**
     * Get the column start value.
     */
    public function getColumnStart(): int|string|array|null
    {
        return $this->evaluate($this->columnStart);
    }
}
