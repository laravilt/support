<?php

use Laravilt\Support\Component;

beforeEach(function () {
    $this->component = new class('test-component') extends Component
    {
        protected string $view = 'test-view';

        public function __construct(string $name)
        {
            $this->name = $name;
            $this->setUp();
        }
    };
});

it('can be instantiated with make method', function () {
    $component = TestComponent::make('test');

    expect($component)->toBeInstanceOf(TestComponent::class)
        ->and($component->getName())->toBe('test');
});

it('has a unique name', function () {
    expect($this->component->getName())->toBe('test-component');
});

it('generates unique ID', function () {
    $id = $this->component->getId();

    expect($id)->toBeString()
        ->and($id)->toContain('laravilt-');
});

it('can set custom ID', function () {
    $this->component->id('custom-id');

    expect($this->component->getId())->toBe('custom-id');
});

it('can set and get label', function () {
    $this->component->label('Test Label');

    expect($this->component->getLabel())->toBe('Test Label');
});

it('can set and get placeholder', function () {
    $this->component->placeholder('Enter text');

    expect($this->component->getPlaceholder())->toBe('Enter text');
});

it('can set and get helper text', function () {
    $this->component->helperText('This is a hint');

    expect($this->component->getHelperText())->toBe('This is a hint');
});

it('can set and get state', function () {
    $this->component->state('test value');

    expect($this->component->getState())->toBe('test value')
        ->and($this->component->hasState())->toBeTrue();
});

it('returns default state when no state is set', function () {
    $this->component->default('default value');

    expect($this->component->getState())->toBe('default value');
});

it('can be hidden', function () {
    $this->component->hidden();

    expect($this->component->isHidden())->toBeTrue()
        ->and($this->component->isVisible())->toBeFalse();
});

it('can be shown', function () {
    $this->component->hidden();
    $this->component->visible();

    expect($this->component->isVisible())->toBeTrue()
        ->and($this->component->isHidden())->toBeFalse();
});

it('can be disabled', function () {
    $this->component->disabled();

    expect($this->component->isDisabled())->toBeTrue()
        ->and($this->component->isEnabled())->toBeFalse();
});

it('can be readonly', function () {
    $this->component->readonly();

    expect($this->component->isReadonly())->toBeTrue();
});

it('can be required', function () {
    $this->component->required();

    expect($this->component->isRequired())->toBeTrue();
});

it('can set column span', function () {
    $this->component->columnSpan(6);

    expect($this->component->getColumnSpan())->toBe(6);
});

it('can set full column span', function () {
    $this->component->columnSpanFull();

    expect($this->component->getColumnSpan())->toBe('full');
});

it('can set metadata', function () {
    $this->component->meta(['key' => 'value']);

    expect($this->component->getMeta())->toBe(['key' => 'value']);
});

it('can merge metadata', function () {
    $this->component->meta(['key1' => 'value1']);
    $this->component->meta(['key2' => 'value2']);

    expect($this->component->getMeta())->toBe([
        'key1' => 'value1',
        'key2' => 'value2',
    ]);
});

it('serializes to laravilt props', function () {
    $this->component
        ->label('Test Label')
        ->placeholder('Test Placeholder')
        ->helperText('Test Helper')
        ->state('test value')
        ->required()
        ->disabled();

    $props = $this->component->toLaraviltProps();

    expect($props)->toHaveKeys([
        'component',
        'id',
        'name',
        'state',
        'label',
        'placeholder',
        'helperText',
        'hidden',
        'disabled',
        'readonly',
        'required',
        'columnSpan',
        'rtl',
        'theme',
        'locale',
        'meta',
    ])
        ->and($props['name'])->toBe('test-component')
        ->and($props['label'])->toBe('Test Label')
        ->and($props['placeholder'])->toBe('Test Placeholder')
        ->and($props['helperText'])->toBe('Test Helper')
        ->and($props['state'])->toBe('test value')
        ->and($props['required'])->toBeTrue()
        ->and($props['disabled'])->toBeTrue();
});

it('converts to array', function () {
    $array = $this->component->toArray();

    expect($array)->toBeArray()
        ->and($array)->toHaveKey('component');
});

it('converts to json', function () {
    $json = $this->component->toJson();

    expect($json)->toBeString()
        ->and(json_decode($json, true))->toBeArray();
});

it('evaluates closures for dynamic values', function () {
    $this->component->label(fn () => 'Dynamic Label');

    expect($this->component->getLabel())->toBe('Dynamic Label');
});

it('evaluates closures for conditional visibility', function () {
    $show = false;
    $this->component->visible(function () use (&$show) {
        return $show;
    });

    expect($this->component->isVisible())->toBeFalse();

    $show = true;
    expect($this->component->isVisible())->toBeTrue();
});

it('gets component type as snake_case', function () {
    $component = TestInputComponent::make('test');
    $props = $component->toLaraviltProps();

    expect($props['component'])->toBe('test_input_component');
});

it('renders to html when visible', function () {
    $this->component->label('Test Label');
    $this->component->state('Test Content');

    $html = $this->component->render();

    expect($html)->toBeString()
        ->and($html)->toContain('test-component')
        ->and($html)->toContain('Test Label')
        ->and($html)->toContain('Test Content');
});

it('renders empty string when hidden', function () {
    $this->component->hidden();
    $html = $this->component->render();

    expect($html)->toBe('');
});

// Helper test components
class TestComponent extends Component
{
    protected string $view = 'test-view';
}

class TestInputComponent extends Component
{
    protected string $view = 'test-input-view';
}
