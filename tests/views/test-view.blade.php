<div class="test-component" data-id="{{ $component->getId() }}">
    @if ($component->getLabel())
        <label>{{ $component->getLabel() }}</label>
    @endif

    @if ($component->getState())
        <div class="content">{!! $component->getState() !!}</div>
    @endif

    @if ($component->getHelperText())
        <small>{{ $component->getHelperText() }}</small>
    @endif
</div>
