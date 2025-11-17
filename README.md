# Laravilt Support Package

[![Latest Version](https://img.shields.io/packagist/v/laravilt/support.svg)](https://packagist.org/packages/laravilt/support)
[![Tests](https://github.com/laravilt/support/actions/workflows/tests.yml/badge.svg)](https://github.com/laravilt/support/actions/workflows/tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/laravilt/support.svg)](https://packagist.org/packages/laravilt/support)
[![License](https://img.shields.io/packagist/l/laravilt/support.svg)](https://packagist.org/packages/laravilt/support)

Foundation package for the Laravilt framework. Provides the base Component class, Laravilt Core SPA functionality, and essential utilities.

## Features

- **🎯 Base Component Class** - Foundation for all Laravilt UI components
- **🔄 Multi-Platform Serialization** - Serialize to Laravilt (Blade+Vue), REST API, and Flutter
- **🌐 SPA Navigation** - Built-in single-page application functionality via Laravilt Core
- **🌍 RTL/LTR Support** - First-class right-to-left language support
- **🎨 Theme System** - Light/dark mode support
- **🔧 Utilities** - Dot notation array access, translation helpers, and more
- **🧪 Fully Tested** - 39 tests with 77 assertions
- **📦 Modern Tooling** - Vite, Vue 3, Tailwind CSS, Pest, PHPStan

## Requirements

- PHP 8.3 or 8.4
- Laravel 12.0 or higher
- Vue 3.4 (for frontend components)

## Installation

Install via Composer:

```bash
composer require laravilt/support
```

The package will be auto-discovered by Laravel.

### Publish Assets

Publish the compiled JavaScript and CSS assets:

```bash
php artisan vendor:publish --tag="laravilt-support-assets"
```

This will copy the built assets to `public/vendor/laravilt/support/`.

### Publish Configuration (Optional)

```bash
php artisan vendor:publish --tag="laravilt-support-config"
```

### Publish Translations (Optional)

```bash
php artisan vendor:publish --tag="laravilt-support-translations"
```

## Quick Start

### 1. Creating a Custom Component

Extend the base `Component` class to create your own components:

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

    public function toLaraviltProps(): array
    {
        return array_merge(parent::toLaraviltProps(), [
            'type' => $this->getType(),
        ]);
    }
}
```

### 2. Using Your Component

```php
use App\Components\AlertComponent;

$alert = AlertComponent::make('success-alert')
    ->label('Success!')
    ->type('success')
    ->state('Your changes have been saved.');

// Serialize for Laravilt (Blade + Vue)
$laraviltProps = $alert->toLaraviltProps();

// Serialize for REST API
$apiProps = $alert->toApiProps();

// Serialize for Flutter
$flutterProps = $alert->toFlutterProps();
```

### 3. Using Laravilt Core Components

#### SPA Links

```blade
<x-laravilt-link href="/dashboard">
    Go to Dashboard
</x-laravilt-link>
```

#### Modals

```blade
<x-laravilt-modal name="confirm-delete" :open="false" title="Confirm Deletion">
    Are you sure you want to delete this item?
</x-laravilt-modal>
```

#### Generic Components

```blade
<x-laravilt-component name="my-component" :data="['message' => 'Hello World']">
    <div>{{ message }}</div>
</x-laravilt-component>
```

## Core Concepts

### Base Component Class

The `Component` class provides:

- **Factory Pattern** - Create instances via `Component::make()`
- **Labels** - Set user-facing labels
- **State Management** - Store and retrieve component values
- **Visibility Control** - Show/hide components conditionally
- **Helper Text** - Add hints and descriptions
- **Column Spanning** - Grid layout support
- **Metadata** - Store arbitrary data
- **Multi-Platform Serialization** - One component, multiple outputs

#### Available Methods

```php
// Creation
Component::make(string $name): static

// Labeling
->label(string|Closure $label): static
->getLabel(): ?string

// State
->state(mixed $state): static
->getState(): mixed
->default(mixed $state): static

// Visibility
->hidden(bool|Closure $condition = true): static
->visible(bool|Closure $condition = true): static
->isHidden(): bool
->isVisible(): bool

// Helper Text
->helperText(string|Closure $text): static
->hint(string|Closure $text): static
->getHelperText(): ?string

// Column Span
->columnSpan(int|string|Closure $span): static
->columnSpanFull(): static
->getColumnSpan(): int|string|null

// Metadata
->meta(array $meta): static
->getMeta(): array

// Serialization
->toLaraviltProps(): array
->toApiProps(): array
->toFlutterProps(): array
->toArray(): array
->toJson(int $options = 0): string
```

### Contracts

The package provides four contracts:

#### Buildable
```php
interface Buildable
{
    public static function make(string $name): static;
}
```

#### HasLabel
```php
interface HasLabel
{
    public function label(string|Closure $label): static;
    public function getLabel(): ?string;
}
```

#### HasState
```php
interface HasState
{
    public function state(mixed $state): static;
    public function getState(): mixed;
}
```

#### Serializable
```php
interface Serializable
{
    public function toLaraviltProps(): array;
    public function toApiProps(): array;
    public function toFlutterProps(): array;
}
```

### Concerns (Traits)

Reusable traits for component functionality:

- `EvaluatesClosures` - Evaluate closures or return direct values
- `HasLabel` - Label management
- `HasVisibility` - Show/hide logic
- `HasColumnSpan` - Grid column spanning
- `HasHelperText` - Helper/hint text
- `InteractsWithState` - State management

### Utilities

#### Get - Dot Notation Array Access

```php
use Laravilt\Support\Utilities\Get;

$data = [
    'user' => [
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ],
];

$name = Get::value($data, 'user.name'); // 'John Doe'
$phone = Get::value($data, 'user.phone', 'N/A'); // 'N/A' (default)
```

#### Set - Dot Notation Array Manipulation

```php
use Laravilt\Support\Utilities\Set;

$data = [];
Set::value($data, 'user.name', 'John Doe');
Set::value($data, 'user.email', 'john@example.com');

// Result: ['user' => ['name' => 'John Doe', 'email' => 'john@example.com']]
```

#### Translator - RTL Detection & Translation Helpers

```php
use Laravilt\Support\Utilities\Translator;

// Check if locale is RTL
Translator::isRTL('ar'); // true
Translator::isRTL('en'); // false

// Get text direction
Translator::direction('ar'); // 'rtl'
Translator::direction('en'); // 'ltr'

// Get RTL locales
$rtlLocales = Translator::getRTLLocales(); // ['ar', 'he', 'fa', 'ur', ...]

// Add custom RTL locale
Translator::addRTLLocale('custom');
```

## Laravilt Core (SPA)

Laravilt Core provides single-page application functionality:

### Features

- **SPA Navigation** - Navigate without full page reloads
- **Component Mounting** - Automatic Vue component initialization
- **Event Bus** - Inter-component communication
- **History API** - Browser back/forward support
- **Modal Management** - Open/close modals programmatically

### JavaScript Usage

```javascript
// Programmatic navigation
window.Laravilt.navigate('/dashboard', 'GET');

// Event bus
window.Laravilt.eventBus.on('user:updated', (user) => {
    console.log('User updated:', user);
});

window.Laravilt.eventBus.emit('user:updated', { id: 1, name: 'John' });

// Modal control
window.Laravilt.eventBus.emit('modal:open', 'confirm-delete');
window.Laravilt.eventBus.emit('modal:close', 'confirm-delete');
```

### Vue Plugin

Use Laravilt Core as a Vue plugin:

```javascript
import { createApp } from 'vue';
import LaraviltCore from '@laravilt/support';

const app = createApp({});
app.use(LaraviltCore);
app.mount('#app');
```

## Translations

The package includes translations for English and Arabic:

### Available Keys

```php
// Common actions
__('laravilt-support::support.common.save')
__('laravilt-support::support.common.cancel')
__('laravilt-support::support.common.delete')
__('laravilt-support::support.common.edit')

// Actions
__('laravilt-support::support.actions.view')
__('laravilt-support::support.actions.export')

// Labels
__('laravilt-support::support.labels.name')
__('laravilt-support::support.labels.email')

// Messages
__('laravilt-support::support.messages.created')
__('laravilt-support::support.messages.updated')
```

### Supported Locales

- English (`en`)
- Arabic (`ar`) with full RTL support

## RTL Support

The package has first-class RTL support:

### Automatic Detection

```php
// Set locale
app()->setLocale('ar');

// Components automatically detect RTL
$component = MyComponent::make('test');
$props = $component->toLaraviltProps();
// $props['rtl'] === true
// $props['locale'] === 'ar'
```

### In Blade Templates

```blade
<!DOCTYPE html>
<html dir="{{ \Laravilt\Support\Utilities\Translator::direction() }}">
```

### In JavaScript

```javascript
const props = component.toLaraviltProps();
if (props.rtl) {
    // Apply RTL styles
}
```

## Theme Support

Components support light/dark themes:

```php
// Set theme in session
session(['theme' => 'dark']);

// Components automatically include theme
$component = MyComponent::make('test');
$props = $component->toLaraviltProps();
// $props['theme'] === 'dark'
```

## Testing

The package includes comprehensive tests:

```bash
# Run tests
composer test

# Run tests with coverage
composer test-coverage

# Run specific test
vendor/bin/pest --filter=ComponentTest
```

### Writing Tests

```php
use Laravilt\Support\Component;

test('can create component', function () {
    $component = TestComponent::make('test')
        ->label('My Label')
        ->state('test-value');

    expect($component->getLabel())->toBe('My Label')
        ->and($component->getState())->toBe('test-value');
});
```

## Code Quality

```bash
# Format code with Laravel Pint
composer format

# Run static analysis with PHPStan
composer analyse

# Run all quality checks
composer test && composer analyse && composer format
```

## Development

### Building Assets

```bash
cd packages/support

# Install dependencies
npm install

# Development build (watch mode)
npm run dev

# Production build
npm run build
```

### File Structure

```
packages/support/
├── src/
│   ├── Component.php              # Base component class
│   ├── Contracts/                 # Interfaces
│   ├── Concerns/                  # Reusable traits
│   ├── Utilities/                 # Helper classes
│   ├── LaraviltCore/              # SPA functionality
│   └── SupportPlugin.php          # Service provider
├── resources/
│   ├── views/                     # Blade templates
│   ├── js/                        # Vue components & utils
│   ├── css/                       # Styles
│   └── lang/                      # Translations
├── dist/                          # Built assets
├── tests/                         # Test suite
└── composer.json
```

## Examples

### Example 1: Alert Component

```php
class AlertComponent extends Component
{
    protected string $view = 'alert';
    protected string $type = 'info';

    public function success(): static
    {
        $this->type = 'success';
        return $this;
    }

    public function error(): static
    {
        $this->type = 'error';
        return $this;
    }

    public function toLaraviltProps(): array
    {
        return array_merge(parent::toLaraviltProps(), [
            'type' => $this->type,
        ]);
    }
}

// Usage
$alert = AlertComponent::make('alert')
    ->success()
    ->label('Success!')
    ->state('Your changes have been saved.');
```

### Example 2: Conditional Visibility

```php
$component = MyComponent::make('admin-only')
    ->label('Admin Settings')
    ->hidden(fn () => !auth()->user()->isAdmin());

if (!$component->isHidden()) {
    // Render component
}
```

### Example 3: Dynamic Labels

```php
$component = MyComponent::make('user-name')
    ->label(fn () => 'Welcome, ' . auth()->user()->name);

echo $component->getLabel(); // "Welcome, John Doe"
```

## API Reference

See the [plan documentation](../../plan/phase-1/01-support-package.md) for detailed API specifications.

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please email info@3x1.io instead of using the issue tracker.

## Credits

- [Fady Mondy](https://github.com/fadymondy)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Changelog

Please see [CHANGELOG.md](CHANGELOG.md) for recent changes.
