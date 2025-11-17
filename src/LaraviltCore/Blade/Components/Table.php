<?php

namespace Laravilt\Support\LaraviltCore\Blade\Components;

use Illuminate\View\Component;

/**
 * Laravilt Table Component
 *
 * Blade component for data tables with SPA-enabled pagination and sorting.
 */
class Table extends Component
{
    /** Table columns configuration */
    public array $columns;

    /** Table data rows */
    public array $rows;

    /** Table CSS classes */
    public string $class;

    /** Enable striped rows */
    public bool $striped;

    /** Enable hover effect */
    public bool $hoverable;

    /** Enable borders */
    public bool $bordered;

    /** Enable SPA pagination */
    public bool $spa;

    /** Component props data */
    public ?array $data;

    /** Table ID */
    public ?string $tableId;

    /** Enable sorting */
    public bool $sortable;

    /** Current sort column */
    public ?string $sortBy;

    /** Sort direction (asc/desc) */
    public string $sortDirection;

    /**
     * Create a new component instance.
     */
    public function __construct(
        array $columns = [],
        array $rows = [],
        string $class = '',
        bool $striped = true,
        bool $hoverable = true,
        bool $bordered = false,
        bool $spa = true,
        ?array $data = null,
        ?string $tableId = null,
        bool $sortable = true,
        ?string $sortBy = null,
        string $sortDirection = 'asc'
    ) {
        $this->columns = $columns;
        $this->rows = $rows;
        $this->class = $class;
        $this->striped = $striped;
        $this->hoverable = $hoverable;
        $this->bordered = $bordered;
        $this->spa = $spa;
        $this->data = $data;
        $this->tableId = $tableId ?? 'laravilt-table-' . uniqid();
        $this->sortable = $sortable;
        $this->sortBy = $sortBy;
        $this->sortDirection = $sortDirection;
    }

    /**
     * Get table CSS classes.
     */
    public function getTableClasses(): string
    {
        $classes = ['laravilt-table'];

        if ($this->striped) {
            $classes[] = 'striped';
        }

        if ($this->hoverable) {
            $classes[] = 'hoverable';
        }

        if ($this->bordered) {
            $classes[] = 'bordered';
        }

        if ($this->class) {
            $classes[] = $this->class;
        }

        return implode(' ', $classes);
    }

    /**
     * Get table attributes as array.
     */
    public function getTableAttributes(): array
    {
        $attributes = [
            'id' => $this->tableId,
            'class' => $this->getTableClasses(),
        ];

        if ($this->spa) {
            $attributes['data-laravilt-table'] = 'true';
        }

        if ($this->data) {
            $attributes['data-laravilt-props'] = json_encode($this->data);
        }

        if ($this->sortable) {
            $attributes['data-sortable'] = 'true';
            if ($this->sortBy) {
                $attributes['data-sort-by'] = $this->sortBy;
                $attributes['data-sort-direction'] = $this->sortDirection;
            }
        }

        return $attributes;
    }

    /**
     * Check if column is sortable.
     */
    public function isColumnSortable(array $column): bool
    {
        return $this->sortable && ($column['sortable'] ?? true);
    }

    /**
     * Get sort icon for column.
     */
    public function getSortIcon(string $columnKey): string
    {
        if ($this->sortBy !== $columnKey) {
            return '↕';
        }

        return $this->sortDirection === 'asc' ? '↑' : '↓';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('laravilt::blade.table');
    }
}
