<div
    data-laravilt-component="{{ $name }}"
    data-laravilt-props="{{ json_encode($data) }}"
    data-laravilt-key="{{ uniqid('laravilt-') }}"
>
    {{ $slot }}
</div>
