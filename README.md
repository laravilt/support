![Screenshot](https://raw.githubusercontent.com/laravilt/support/master/arts/cover.jpg)

# Laravilt Support

[![Latest Version](https://img.shields.io/packagist/v/laravilt/support.svg)](https://packagist.org/packages/laravilt/support)
[![Tests](https://github.com/laravilt/support/actions/workflows/tests.yml/badge.svg)](https://github.com/laravilt/support/actions/workflows/tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/laravilt/support.svg)](https://packagist.org/packages/laravilt/support)
[![License](https://img.shields.io/packagist/l/laravilt/support.svg)](https://packagist.org/packages/laravilt/support)

Foundation package for the Laravilt framework. Provides the base Component class, complete SPA infrastructure with Vue 3, and essential utilities for building modern Laravel applications.

## Features

### Core SPA Infrastructure ✅
- **🚀 Complete SPA Engine** - Full single-page application framework (Laravilt.js - 814 lines)
- **⚡ Vue 3 Integration** - Root app component with KeepAlive, modal stack, and error handling
- **📊 Progress Bar** - NProgress integration with auto-start and upload tracking
- **🔌 Vue Plugin System** - Easy installation with configurable options
- **🔄 SPA Navigation** - Navigate without full page reloads with history API support
- **📦 Modal Stack Management** - Multiple modals, slideovers with backdrop blur
- **🍞 Toast Notifications** - Built-in toast system for user feedback
- **💾 State Management** - Remember/restore/forget state persistence
- **🔄 Lazy Loading & Rehydration** - Optimize component loading strategies

### Component Foundation
- **🎯 Base Component Class** - Foundation for all Laravilt UI components
- **🔄 Multi-Platform Serialization** - Serialize to Laravilt (Blade+Vue), REST API, and Flutter
- **🌍 RTL/LTR Support** - First-class right-to-left language support
- **🎨 Theme System** - Light/dark mode support
- **🔧 Utilities** - Dot notation array access, translation helpers, and more

### Developer Experience
- **🧪 Fully Tested** - 39 tests with 77 assertions
- **📦 Modern Tooling** - Vite, Vue 3, Tailwind CSS, Pest, PHPStan
- **🎨 Component Generator** - Artisan command for quick scaffolding

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

### 1. Generate a Component

Use the Artisan command to quickly scaffold a new component:

```bash
php artisan laravilt:component Alert
```

This creates:
- `app/Components/Alert.php` - Component class
- `resources/views/components/alert.blade.php` - View template

### 2. Creating a Custom Component

Extend the base `Component` class to create your own components (or use the generator):

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

## Artisan Commands

### Generate Component

Quickly scaffold a new Laravilt component:

```bash
php artisan laravilt:component Alert
```

This creates:
- `app/Components/Alert.php` - Component class extending `Laravilt\Support\Component`
- `resources/views/components/alert.blade.php` - Blade view template

**Options:**
- `--force` - Overwrite existing files

**Example:**
```bash
php artisan laravilt:component UserProfile --force
```

For more details, see [Component Generator Documentation](docs/component-generator.md).

## Laravilt Core (SPA)

Laravilt Core is a complete SPA framework providing powerful features for building modern single-page applications with Laravel and Vue 3.

### Core Engine (Laravilt.js)

The Laravilt.js engine (814 lines) provides 30+ API methods:

**Navigation & Page Management**
```javascript
// Navigate to a new page
Laravilt.visit('/dashboard', { method: 'GET', data: {} });

// Replace current page without history entry
Laravilt.replace('/users', { method: 'GET', preserveScroll: true });

// Refresh current page
Laravilt.refresh({ preserveScroll: true });

// Replace URL without reload
Laravilt.replaceUrlOfCurrentPage('/users?page=2');
```

**Modal & Slideover Stack**
```javascript
// Open modal
Laravilt.modal('/users/create');

// Open slideover
Laravilt.slideover('/notifications', { position: 'right' });

// Access modal stack
console.log(Laravilt.currentStack.value); // ['modal-1', 'modal-2']
```

**Toast Notifications**
```javascript
// Push toast
Laravilt.pushToast({
    message: 'User created successfully',
    type: 'success',
    dismissible: true,
});

// Dismiss toast
Laravilt.dismissToast('toast-id');

// Access toasts
console.log(Laravilt.toasts.value);
console.log(Laravilt.toastsReversed.value);
```

**State Management**
```javascript
// Remember state (persists across navigation)
Laravilt.remember('form-data', { name: 'John', email: 'john@example.com' });

// Restore state
const formData = Laravilt.restore('form-data');

// Forget state
Laravilt.forget('form-data');
```

**Lazy Loading & Rehydration**
```javascript
// Lazy load component
Laravilt.lazy('/components/chart', '#chart-container');

// Rehydrate component
Laravilt.rehydrate('/components/table', '#table-container');

// Get dynamic component HTML
const html = Laravilt.htmlForDynamicComponent('my-component');
```

**Confirm Dialogs**
```javascript
// Show confirm modal
Laravilt.confirm({
    title: 'Delete User',
    message: 'Are you sure?',
    confirmButton: 'Delete',
    cancelButton: 'Cancel',
    onConfirm: () => { /* delete logic */ },
});

// Clear confirm modal
Laravilt.clearConfirmModal();

// Access confirm state
console.log(Laravilt.confirmModal.value);
```

**Event System**
```javascript
// Register event listener
Laravilt.on('user:updated', (user) => {
    console.log('User updated:', user);
});

// Emit event
Laravilt.emit('user:updated', { id: 1, name: 'John' });

// Remove listener
Laravilt.off('user:updated', callback);
```

**Data Access**
```javascript
// Access shared data from server
const config = Laravilt.sharedData.value;

// Access flash data
const message = Laravilt.flashData.value;

// Validation errors
if (Laravilt.hasValidationErrors.value) {
    const errors = Laravilt.validationErrors.value;
}
```

**File Downloads**
```javascript
// Download file from blob
Laravilt.downloadFromBlob(blob, 'report.pdf');

// Download from URL
Laravilt.downloadFromURL('/export/users', 'users.xlsx');
```

### Vue 3 Integration

Install as a Vue plugin with full configuration:

```javascript
import { createApp } from 'vue';
import LaraviltPlugin from '@laravilt/support';
import LaraviltApp from '@laravilt/support/LaraviltApp';

const app = createApp(LaraviltApp);

app.use(LaraviltPlugin, {
    // Maximum KeepAlive components (default: 10)
    max_keep_alive: 10,

    // Component prefix (default: 'Laravilt')
    prefix: 'Laravilt',

    // Transform regular anchors to SPA links (default: false)
    transform_anchors: false,

    // Link component name (default: 'Link')
    link_component: 'Link',

    // Progress bar configuration
    progress_bar: {
        delay: 250,        // Delay before showing (ms)
        color: '#4B5563',  // Progress bar color
        css: true,         // Inject default CSS
        spinner: false,    // Show spinner
    },

    // View transitions API (default: false)
    view_transitions: true,

    // Suppress Vue compiler errors (default: false)
    suppress_compile_errors: false,

    // Register custom components
    components: {
        'MyCustomComponent': MyComponent,
    },
});

app.mount('#app');
```

### Progress Bar

Automatic progress indication with NProgress:

```javascript
import { LaraviltProgress } from '@laravilt/support';

// Initialize manually
LaraviltProgress.init({
    delay: 250,
    color: '#4B5563',
    css: true,
    spinner: false,
});

// Automatically tracks:
// - laravilt:internal:request
// - laravilt:internal:request-progress
// - laravilt:internal:request-response
// - laravilt:internal:request-error
```

### Vue Components

**LaraviltApp.vue** - Root application component
- KeepAlive page caching with configurable max
- Modal stack rendering with backdrop blur
- Server error overlay with iframe
- Meta tag management
- View Transitions API support

**Link.vue** - SPA navigation link
**Modal.vue** - Modal/slideover component
**Render.vue** - Dynamic HTML renderer
**ComponentRenderer.vue** - Component renderer
**ServerError.vue** - Error overlay display

### SSR Support

Laravilt Core fully supports server-side rendering:

```javascript
// Detect SSR mode
if (Laravilt.isSsr) {
    // Skip client-only logic
}
```

### Events Reference

**Internal Events** (auto-emitted by framework):
- `laravilt:internal:request` - Request started
- `laravilt:internal:request-progress` - Upload/download progress
- `laravilt:internal:request-response` - Request completed
- `laravilt:internal:request-error` - Request failed

**Public Events** (for your app):
- `laravilt:navigated` - Page navigation completed
- `laravilt:modal:opened` - Modal opened
- `laravilt:modal:closed` - Modal closed
- `laravilt:toast:pushed` - Toast added
- `laravilt:toast:dismissed` - Toast removed

### Best Practices

1. **Use the provided Link component** for navigation
2. **Leverage state management** for forms and filters
3. **Clean up event listeners** in component unmount
4. **Use lazy loading** for heavy components
5. **Configure KeepAlive wisely** based on your app's needs

For detailed documentation, see [Laravilt Core Documentation](docs/laravilt-core.md).

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
