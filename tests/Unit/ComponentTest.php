<?php

use Laravilt\Support\Component;

/**
 * Test Component for testing purposes.
 */
class TestComponent extends Component
{
    protected string $view = 'test-component';

    public function content(string $content): static
    {
        $this->state = $content;

        return $this;
    }
}

test('can create component using make factory', function () {
    $component = TestComponent::make('test');

    expect($component)->toBeInstanceOf(Component::class)
        ->and($component->getName())->toBe('test');
});

test('can set and get label', function () {
    $component = TestComponent::make('test')->label('Test Label');

    expect($component->getLabel())->toBe('Test Label');
});

test('can evaluate closure label', function () {
    $component = TestComponent::make('test')->label(fn () => 'Dynamic Label');

    expect($component->getLabel())->toBe('Dynamic Label');
});

test('can set and get state', function () {
    $component = TestComponent::make('test')->state('test-value');

    expect($component->getState())->toBe('test-value');
});

test('can set and get helper text', function () {
    $component = TestComponent::make('test')->helperText('Help text');

    expect($component->getHelperText())->toBe('Help text');
});

test('can set column span', function () {
    $component = TestComponent::make('test')->columnSpan(2);

    expect($component->getColumnSpan())->toBe(2);
});

test('can set column span full', function () {
    $component = TestComponent::make('test')->columnSpanFull();

    expect($component->getColumnSpan())->toBe('full');
});

test('can hide component', function () {
    $component = TestComponent::make('test')->hidden();

    expect($component->isHidden())->toBeTrue()
        ->and($component->isVisible())->toBeFalse();
});

test('can show component conditionally', function () {
    $component = TestComponent::make('test')->visible(false);

    expect($component->isHidden())->toBeTrue();
});

test('can serialize to laravilt props', function () {
    $component = TestComponent::make('test')
        ->label('Test')
        ->state('value')
        ->helperText('Help');

    $props = $component->toLaraviltProps();

    expect($props)->toBeArray()
        ->and($props)->toHaveKeys(['id', 'name', 'label', 'state', 'helperText', 'hidden', 'columnSpan', 'rtl', 'theme', 'locale'])
        ->and($props['name'])->toBe('test')
        ->and($props['label'])->toBe('Test')
        ->and($props['state'])->toBe('value')
        ->and($props['helperText'])->toBe('Help');
});

test('can serialize to api props', function () {
    $component = TestComponent::make('test')
        ->label('Test Label')
        ->state('test-value');

    $props = $component->toApiProps();

    expect($props)->toBeArray()
        ->and($props)->toHaveKeys(['type', 'name', 'value', 'label'])
        ->and($props['type'])->toBe('TestComponent')
        ->and($props['name'])->toBe('test')
        ->and($props['value'])->toBe('test-value')
        ->and($props['label'])->toBe('Test Label');
});

test('can serialize to flutter props', function () {
    $component = TestComponent::make('test')
        ->label('Test Label')
        ->state('test-value');

    $props = $component->toFlutterProps();

    expect($props)->toBeArray()
        ->and($props)->toHaveKeys(['widget', 'name', 'value', 'label'])
        ->and($props['widget'])->toBe('TestComponent')
        ->and($props['name'])->toBe('test');
});

test('detects rtl locale', function () {
    app()->setLocale('ar');

    $component = TestComponent::make('test');
    $props = $component->toLaraviltProps();

    expect($props['rtl'])->toBeTrue();
});

test('detects ltr locale', function () {
    app()->setLocale('en');

    $component = TestComponent::make('test');
    $props = $component->toLaraviltProps();

    expect($props['rtl'])->toBeFalse();
});

test('can set metadata', function () {
    $component = TestComponent::make('test')->meta(['key' => 'value']);

    expect($component->getMeta())->toBe(['key' => 'value']);
});

test('can convert to array', function () {
    $component = TestComponent::make('test')->label('Test');

    expect($component->toArray())->toBeArray()
        ->and($component->toArray()['name'])->toBe('test');
});

test('can convert to json', function () {
    $component = TestComponent::make('test')->label('Test');
    $json = $component->toJson();

    expect($json)->toBeString()
        ->and(json_decode($json, true))->toBeArray();
});
