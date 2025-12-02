# Laravilt Support Documentation

Foundation package providing base components, utilities, and contracts for all Laravilt packages.

## Table of Contents

1. [Getting Started](#getting-started)
2. [Architecture](#architecture)
3. [Base Component](#base-component)
4. [Concerns](#concerns)
5. [Utilities](#utilities)
6. [Contracts](#contracts)
7. [API Reference](#api-reference)
8. [MCP Server Integration](mcp-server.md)

## Overview

Laravilt Support is the foundation package that provides:

- **Base Component Class**: Foundation for all UI components
- **Concerns (Traits)**: Reusable component behaviors
- **Utilities**: Helper classes for common operations
- **Contracts**: Interfaces for consistent implementations
- **Laravilt Core**: Central configuration and state management
- **Serialization**: Multi-platform data serialization (Laravilt, API, Flutter)

## Key Features

### 🏗️ Base Component
- Factory pattern via `make()`
- Multi-platform serialization
- RTL/LTR support
- Theme support
- Visibility control
- State management

### 🎨 Concerns (Traits)
- `CanBeDisabled`: Disable components
- `CanBeHidden`: Hide components conditionally
- `CanBeReadonly`: Make components readonly
- `CanBeRequired`: Mark components as required
- `EvaluatesClosures`: Evaluate closures with context
- `HasColumnSpan`: Grid column spanning
- `HasHelperText`: Helper text support
- `HasId`: Unique identifiers
- `HasLabel`: Labels and descriptions
- `HasPlaceholder`: Placeholder text
- `HasState`: Component state management
- `HasVisibility`: Conditional visibility
- `InteractsWithState`: State interaction utilities

### 🛠️ Utilities
- **Get**: Retrieve field values from state
- **Set**: Update field values in state
- **Str**: String manipulation helpers
- **Arr**: Array manipulation helpers

### 📝 Contracts
- `Buildable`: Components that can be built
- `Serializable`: Components that can be serialized
- `InertiaSerializable`: Components for Inertia.js
- `HasSchema`: Components with nested schemas

## System Requirements

- PHP 8.3+
- Laravel 12+

## Installation

```bash
composer require laravilt/support
```

The service provider is auto-discovered and will register automatically.

## Base Component

All Laravilt components extend the base `Component` class:

```php
use Laravilt\Support\Component;

class CustomField extends Component
{
    protected string $view = 'laravilt-custom::field';

    protected function setUp(): void
    {
        parent::setUp();
        // Initialize component here
    }

    public function toLaraviltProps(): array
    {
        return array_merge(parent::toLaraviltProps(), [
            'customProperty' => $this->getCustomProperty(),
        ]);
    }
}
```

### Creating Components

```php
$component = CustomField::make('field_name')
    ->label('Field Label')
    ->placeholder('Enter value')
    ->required()
    ->helperText('This is a helpful hint')
    ->disabled(false)
    ->visible(true);
```

## Concerns (Traits)

### CanBeDisabled

```php
use Laravilt\Support\Concerns\CanBeDisabled;

class CustomComponent extends Component
{
    use CanBeDisabled;
}

// Usage
$component->disabled(true);
$component->disabled(fn ($record) => $record->is_locked);
```

### CanBeHidden

```php
use Laravilt\Support\Concerns\CanBeHidden;

class CustomComponent extends Component
{
    use CanBeHidden;
}

// Usage
$component->hidden(true);
$component->hidden(fn ($record) => $record->is_archived);
```

### HasVisibility

```php
use Laravilt\Support\Concerns\HasVisibility;

class CustomComponent extends Component
{
    use HasVisibility;
}

// Usage
$component->visible(true);
$component->visible(fn (Get $get) => $get('show_advanced') === true);
```

### EvaluatesClosures

```php
use Laravilt\Support\Concerns\EvaluatesClosures;

class CustomComponent extends Component
{
    use EvaluatesClosures;

    public function process($value)
    {
        // Evaluates closures with automatic dependency injection
        return $this->evaluate($value, [
            'record' => $this->getRecord(),
            'data' => $this->getData(),
        ]);
    }
}
```

### HasColumnSpan

```php
use Laravilt\Support\Concerns\HasColumnSpan;

class CustomComponent extends Component
{
    use HasColumnSpan;
}

// Usage
$component->columnSpan(2); // Span 2 columns
$component->columnSpan('full'); // Full width
$component->columnSpan([
    'default' => 1,
    'sm' => 2,
    'lg' => 3,
]); // Responsive
```

### HasState

```php
use Laravilt\Support\Concerns\HasState;

class CustomComponent extends Component
{
    use HasState;
}

// Usage
$component->default('default value');
$component->afterStateUpdated(function ($value, Set $set) {
    $set('related_field', $value * 2);
});
$component->live(); // Make field reactive
```

## Utilities

### Get Utility

Retrieve field values from state:

```php
use Laravilt\Support\Utilities\Get;

$component->visible(fn (Get $get) => {
    return $get('country') === 'US';
});

// Get nested values
$get('address.city');

// Get with default
$get('optional_field', 'default');
```

### Set Utility

Update field values in state:

```php
use Laravilt\Support\Utilities\Set;

$component->afterStateUpdated(function ($value, Set $set) {
    // Set single value
    $set('related_field', $value * 2);

    // Set nested value
    $set('address.city', 'New York');

    // Set multiple values
    $set([
        'field1' => 'value1',
        'field2' => 'value2',
    ]);
});
```

### Evaluation Context

Components support evaluation context for dynamic behavior:

```php
use Laravilt\Support\Utilities\Get;
use Laravilt\Support\Utilities\Set;

$component
    ->visible(fn (Get $get) => $get('show_field') === true)
    ->default(fn (Get $get) => $get('other_field') * 2)
    ->afterStateUpdated(function ($value, Set $set, Get $get) {
        if ($value > 100) {
            $set('warning', 'Value is high');
        }
    });
```

## Serialization

### Laravilt Props (Inertia)

```php
$component->toLaraviltProps();
```

Returns data formatted for Laravilt/Inertia.js Vue components.

### API Serialization

```php
$component->toApiResponse();
```

Returns data formatted for REST APIs.

### Flutter Serialization

```php
$component->toFlutterProps();
```

Returns data formatted for Flutter mobile apps.

## Contracts

### Buildable

```php
interface Buildable
{
    public static function make(string $name): static;
}
```

### Serializable

```php
interface Serializable
{
    public function toLaraviltProps(): array;
    public function toApiResponse(): array;
    public function toFlutterProps(): array;
}
```

### InertiaSerializable

```php
interface InertiaSerializable
{
    public function toInertiaProps(): array;
}
```

### HasSchema

```php
interface HasSchema
{
    public function schema(array $components): static;
    public function getSchema(): array;
}
```

## Creating Custom Components

### Step 1: Create Component Class

```php
<?php

namespace App\Forms\Components;

use Laravilt\Support\Component;
use Laravilt\Support\Concerns\CanBeDisabled;
use Laravilt\Support\Concerns\CanBeRequired;
use Laravilt\Support\Concerns\HasLabel;
use Laravilt\Support\Concerns\HasState;

class RatingInput extends Component
{
    use CanBeDisabled;
    use CanBeRequired;
    use HasLabel;
    use HasState;

    protected string $view = 'forms.components.rating-input';

    protected int $maxRating = 5;
    protected ?string $icon = 'star';
    protected ?string $color = 'warning';

    public function maxRating(int $max): static
    {
        $this->maxRating = $max;
        return $this;
    }

    public function icon(string $icon): static
    {
        $this->icon = $icon;
        return $this;
    }

    public function color(string $color): static
    {
        $this->color = $color;
        return $this;
    }

    public function toLaraviltProps(): array
    {
        return array_merge(parent::toLaraviltProps(), [
            'maxRating' => $this->maxRating,
            'icon' => $this->icon,
            'color' => $this->color,
        ]);
    }
}
```

### Step 2: Use Custom Component

```php
use App\Forms\Components\RatingInput;

RatingInput::make('rating')
    ->label('Rate this product')
    ->maxRating(5)
    ->icon('star')
    ->color('warning')
    ->required();
```

## Generator Command

```bash
# Generate a custom component
php artisan make:component RatingInput

# Force overwrite existing file
php artisan make:component RatingInput --force
```

## Best Practices

1. **Extend Base Component**: Always extend `Laravilt\Support\Component`
2. **Use Concerns**: Leverage existing concerns instead of duplicating code
3. **Serialize Properly**: Implement `toLaraviltProps()` for frontend integration
4. **Evaluate Closures**: Use `evaluate()` for dynamic values
5. **Type Hints**: Use proper type hints for better IDE support
6. **Document Methods**: Add docblocks for custom methods
7. **Test Components**: Write tests for custom components

## Advanced Examples

### Conditional Visibility

```php
use Laravilt\Support\Utilities\Get;

TextInput::make('state')
    ->visible(fn (Get $get) => $get('country') === 'US')
    ->required(fn (Get $get) => $get('country') === 'US');
```

### Dependent Fields

```php
use Laravilt\Support\Utilities\Get;
use Laravilt\Support\Utilities\Set;

Select::make('country')
    ->options([...])
    ->afterStateUpdated(function ($value, Set $set) {
        $set('state', null); // Reset state when country changes
    })
    ->live();

Select::make('state')
    ->options(fn (Get $get) =>
        $get('country') === 'US'
            ? ['CA' => 'California', 'NY' => 'New York']
            : []
    );
```

### Complex State Management

```php
NumberField::make('quantity')
    ->default(1)
    ->afterStateUpdated(function ($value, Set $set, Get $get) {
        $price = $get('price');
        $set('subtotal', $price * $value);

        $tax = $get('tax_rate') / 100;
        $subtotal = $get('subtotal');
        $set('total', $subtotal + ($subtotal * $tax));
    })
    ->live();
```

## Support

- GitHub Issues: github.com/laravilt/support
- Documentation: docs.laravilt.com
- Discord: discord.laravilt.com
