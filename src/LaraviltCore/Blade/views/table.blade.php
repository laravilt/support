<div {{ $attributes->merge(['class' => 'laravilt-table-wrapper']) }}>
    <table {{ $attributes->only(['id'])->merge($getTableAttributes()) }}>
        @if (count($columns) > 0)
            <thead>
                <tr>
                    @foreach ($columns as $key => $column)
                        @php
                            $columnKey = is_array($column) ? ($column['key'] ?? $key) : $key;
                            $columnLabel = is_array($column) ? ($column['label'] ?? $columnKey) : $column;
                            $isSortable = isColumnSortable(is_array($column) ? $column : ['key' => $key]);
                        @endphp
                        <th
                            @if ($isSortable)
                                class="sortable {{ $sortBy === $columnKey ? 'sorted sorted-' . $sortDirection : '' }}"
                                data-column="{{ $columnKey }}"
                                style="cursor: pointer;"
                            @endif
                        >
                            {{ $columnLabel }}
                            @if ($isSortable)
                                <span class="sort-icon">{{ getSortIcon($columnKey) }}</span>
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>
        @endif

        <tbody>
            @if (count($rows) > 0)
                @foreach ($rows as $row)
                    <tr>
                        @foreach ($columns as $key => $column)
                            @php
                                $columnKey = is_array($column) ? ($column['key'] ?? $key) : $key;
                                $value = is_array($row) ? ($row[$columnKey] ?? '') : ($row->{$columnKey} ?? '');
                            @endphp
                            <td>{{ $value }}</td>
                        @endforeach
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="{{ count($columns) }}" class="text-center">
                        {{ $slot->isEmpty() ? 'No data available' : $slot }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

@if ($spa && $sortable)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('{{ $tableId }}');

    if (!table) return;

    const sortableHeaders = table.querySelectorAll('th.sortable');

    sortableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const column = this.dataset.column;
            const currentSortBy = table.dataset.sortBy;
            const currentDirection = table.dataset.sortDirection || 'asc';

            let newDirection = 'asc';
            if (column === currentSortBy) {
                newDirection = currentDirection === 'asc' ? 'desc' : 'asc';
            }

            // Emit sort event
            window.dispatchEvent(new CustomEvent('laravilt:table-sort', {
                detail: {
                    table: table,
                    column: column,
                    direction: newDirection,
                }
            }));

            // Update table attributes
            table.dataset.sortBy = column;
            table.dataset.sortDirection = newDirection;

            // Update UI
            sortableHeaders.forEach(h => {
                h.classList.remove('sorted', 'sorted-asc', 'sorted-desc');
            });
            this.classList.add('sorted', `sorted-${newDirection}`);

            const icon = this.querySelector('.sort-icon');
            if (icon) {
                icon.textContent = newDirection === 'asc' ? '↑' : '↓';
            }
        });
    });
});
</script>
@endif

<style>
.laravilt-table-wrapper {
    overflow-x: auto;
}

.laravilt-table {
    width: 100%;
    border-collapse: collapse;
}

.laravilt-table th,
.laravilt-table td {
    padding: 0.75rem;
    text-align: left;
}

.laravilt-table.bordered {
    border: 1px solid #e5e7eb;
}

.laravilt-table.bordered th,
.laravilt-table.bordered td {
    border: 1px solid #e5e7eb;
}

.laravilt-table thead th {
    background-color: #f9fafb;
    font-weight: 600;
}

.laravilt-table.striped tbody tr:nth-child(even) {
    background-color: #f9fafb;
}

.laravilt-table.hoverable tbody tr:hover {
    background-color: #f3f4f6;
}

.laravilt-table th.sortable {
    user-select: none;
}

.laravilt-table th.sortable:hover {
    background-color: #f3f4f6;
}

.laravilt-table th.sorted {
    background-color: #e5e7eb;
}

.laravilt-table .sort-icon {
    margin-left: 0.5rem;
    opacity: 0.5;
}

.laravilt-table th.sorted .sort-icon {
    opacity: 1;
}

.laravilt-table .text-center {
    text-align: center;
}
</style>
