# Serialization Guide

Laravilt components support multi-platform serialization, allowing you to use the same component definitions across different outputs: Blade+Vue (Laravilt), REST API, and Flutter.

## Overview

Each component can be serialized to three different formats:

1. **Laravilt** - For Blade templates with Vue.js
2. **REST API** - For API endpoints
3. **Flutter** - For mobile applications

## Serialization Methods

### toLaraviltProps()

Serializes component for Laravilt (Blade + Vue) usage:

```php
$component = MyComponent::make('test')
    ->label('Test Label')
    ->state('test-value');

$props = $component->toLaraviltProps();
```

**Output:**
```php
[
    'id' => 'laravilt-test-abc123',
    'name' => 'test',
    'state' => 'test-value',
    'label' => 'Test Label',
    'helperText' => null,
    'hidden' => false,
    'columnSpan' => null,
    'rtl' => false,
    'theme' => 'light',
    'locale' => 'en',
    'meta' => [],
]
```

### toApiProps()

Serializes component for REST API:

```php
$props = $component->toApiProps();
```

**Output:**
```php
[
    'type' => 'MyComponent',
    'name' => 'test',
    'value' => 'test-value',
    'label' => 'Test Label',
    'helperText' => null,
    'meta' => [],
]
```

### toFlutterProps()

Serializes component for Flutter:

```php
$props = $component->toFlutterProps();
```

**Output:**
```php
[
    'widget' => 'MyComponent',
    'name' => 'test',
    'value' => 'test-value',
    'label' => 'Test Label',
    'helperText' => null,
    'isRTL' => false,
    'meta' => [],
]
```

## Custom Serialization

Override serialization methods to add custom fields:

```php
class AlertComponent extends Component
{
    protected string $type = 'info';

    public function toLaraviltProps(): array
    {
        return array_merge(parent::toLaraviltProps(), [
            'type' => $this->type,
            'icon' => $this->getIcon(),
        ]);
    }

    public function toApiProps(): array
    {
        return array_merge(parent::toApiProps(), [
            'alertType' => $this->type,
        ]);
    }

    public function toFlutterProps(): array
    {
        return array_merge(parent::toFlutterProps(), [
            'alertType' => $this->type,
            'iconData' => $this->getFlutterIcon(),
        ]);
    }
}
```

## Using ComponentSerializer

For batch serialization:

```php
use Laravilt\Support\LaraviltCore\Bridge\ComponentSerializer;

// Single component
$json = ComponentSerializer::toJson($component, 'laravilt');

// Multiple components
$components = [$component1, $component2];
$serialized = ComponentSerializer::serializeMany($components, 'api');
```

## Platform-Specific Considerations

### Laravilt (Blade + Vue)

- Includes `rtl` and `theme` for UI rendering
- Includes `locale` for translations
- Full component metadata

### REST API

- Minimal output for bandwidth efficiency
- Uses `type` instead of `widget`
- Uses `value` instead of `state`

### Flutter

- Uses `widget` to match Flutter conventions
- Includes `isRTL` for text direction
- Optimized for mobile rendering

## JSON Serialization

All components implement `Jsonable`:

```php
$json = $component->toJson();
// or
$json = json_encode($component);
```

## Array Serialization

All components implement `Arrayable`:

```php
$array = $component->toArray();
```

## Examples

### API Endpoint

```php
Route::get('/api/components/{id}', function ($id) {
    $component = MyComponent::find($id);
    return response()->json($component->toApiProps());
});
```

### Flutter Endpoint

```php
Route::get('/flutter/components/{id}', function ($id) {
    $component = MyComponent::find($id);
    return response()->json($component->toFlutterProps());
});
```

### Blade View

```blade
<x-laravilt-component
    name="my-component"
    :data="$component->toLaraviltProps()"
/>
```

## See Also

- [Component Base Class](component-base-class.md)
- [Custom Serialization Example](examples/custom-serialization.md)
