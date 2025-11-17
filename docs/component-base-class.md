# Component Base Class

The `Component` class is the foundation of all Laravilt UI components. It provides a consistent API for creating, configuring, and serializing components across different platforms.

## Overview

Every Laravilt component extends the base `Component` class, which provides:

- **Factory Pattern** - Create instances via `Component::make()`
- **Fluent API** - Chain methods for configuration
- **State Management** - Store and retrieve component values
- **Visibility Control** - Show/hide components conditionally
- **Multi-Platform Serialization** - Serialize to Laravilt, REST API, or Flutter
- **RTL Support** - Automatic right-to-left language support
- **Theme Support** - Light/dark mode integration

## Creating a Component

### Basic Example

```php
<?php

namespace App\Components;

use Laravilt\Support\Component;

class AlertComponent extends Component
{
    protected string $view = 'components.alert';

    protected string $type = 'info';

    public function type(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
```

### Using the Component

```php
$alert = AlertComponent::make('success-alert')
    ->label('Success!')
    ->type('success')
    ->state('Your changes have been saved.');
```

## Available Methods

### Factory Method

```php
Component::make(string $name): static
```

Creates a new instance using the factory pattern.

### Labels

```php
->label(string|Closure $label): static
->getLabel(): ?string
```

Set and retrieve user-facing labels.

### State

```php
->state(mixed $state): static
->getState(): mixed
->default(mixed $state): static
->hasState(): bool
```

Manage component state/value.

### Visibility

```php
->hidden(bool|Closure $condition = true): static
->visible(bool|Closure $condition = true): static
->isHidden(): bool
->isVisible(): bool
```

Control component visibility.

### Helper Text

```php
->helperText(string|Closure $text): static
->hint(string|Closure $text): static
->getHelperText(): ?string
```

Add hints and descriptions.

### Column Span

```php
->columnSpan(int|string|Closure $span): static
->columnSpanFull(): static
->getColumnSpan(): int|string|null
```

Configure grid column spanning.

### Metadata

```php
->meta(array $meta): static
->getMeta(): array
```

Store arbitrary metadata.

## Serialization

### Laravilt (Blade + Vue)

```php
$props = $component->toLaraviltProps();
// Returns: ['id', 'name', 'label', 'state', 'rtl', 'theme', ...]
```

### REST API

```php
$props = $component->toApiProps();
// Returns: ['type', 'name', 'value', 'label', ...]
```

### Flutter

```php
$props = $component->toFlutterProps();
// Returns: ['widget', 'name', 'value', 'label', 'isRTL', ...]
```

## Advanced Features

### Closure Evaluation

All setter methods accept closures for dynamic values:

```php
$component->label(fn() => 'Welcome, ' . auth()->user()->name);
```

### Conditional Visibility

```php
$component->hidden(fn() => !auth()->user()->isAdmin());
```

### Custom Serialization

Override serialization methods for custom data:

```php
public function toLaraviltProps(): array
{
    return array_merge(parent::toLaraviltProps(), [
        'customField' => $this->getCustomField(),
    ]);
}
```

## See Also

- [Serialization Guide](serialization.md)
- [Utilities](utilities.md)
- [Basic Component Example](examples/basic-component.md)
