<div
    data-laravilt-modal="{{ $name }}"
    data-laravilt-open="{{ $open ? 'true' : 'false' }}"
    {{ $attributes->merge(['class' => 'laravilt-modal']) }}
>
    @if($title)
        <div class="laravilt-modal-header">
            <h3>{{ $title }}</h3>
        </div>
    @endif
    <div class="laravilt-modal-content">
        {{ $slot }}
    </div>
</div>
