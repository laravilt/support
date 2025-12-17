![support](https://raw.githubusercontent.com/laravilt/support/master/arts/screenshot.jpg)

# Laravilt Support

[![Latest Stable Version](https://poser.pugx.org/laravilt/support/version.svg)](https://packagist.org/packages/laravilt/support)
[![License](https://poser.pugx.org/laravilt/support/license.svg)](https://packagist.org/packages/laravilt/support)
[![Downloads](https://poser.pugx.org/laravilt/support/d/total.svg)](https://packagist.org/packages/laravilt/support)
[![Dependabot Updates](https://github.com/laravilt/support/actions/workflows/dependabot/dependabot-updates/badge.svg)](https://github.com/laravilt/support/actions/workflows/dependabot/dependabot-updates)
[![PHP Code Styling](https://github.com/laravilt/support/actions/workflows/fix-php-code-styling.yml/badge.svg)](https://github.com/laravilt/support/actions/workflows/fix-php-code-styling.yml)
[![Tests](https://github.com/laravilt/support/actions/workflows/tests.yml/badge.svg)](https://github.com/laravilt/support/actions/workflows/tests.yml)


Foundation package providing base components, utilities, and contracts for all Laravilt packages. Build custom components with reusable traits, utilities, and base classes.

## Features

- 🏗️ **Base Component** - Foundation for all UI components
- 🎨 **Concerns** - 11+ reusable component behaviors (traits)
- 🛠️ **Utilities** - Get, Set, Str, Arr helpers
- 📝 **Contracts** - Interfaces for consistent implementations
- 🔄 **Serialization** - Multi-platform support

## Available Traits

| Trait | Description |
|-------|-------------|
| `CanBeDisabled` | Enable/disable component state |
| `CanBeHidden` | Conditional visibility control |
| `HasActions` | Action button support |
| `HasColor` | Color theming (primary, success, danger, etc.) |
| `HasDescription` | Description/helper text |
| `HasIcon` | Icon display support |
| `HasId` | Unique identifier management |
| `HasLabel` | Label configuration |
| `HasName` | Name attribute handling |
| `HasPlaceholder` | Placeholder text |
| `InteractsWithState` | State management utilities |

## Quick Example

```php
use Laravilt\Support\Component;
use Laravilt\Support\Concerns\HasLabel;
use Laravilt\Support\Concerns\HasIcon;
use Laravilt\Support\Concerns\HasColor;

class MyComponent extends Component
{
    use HasLabel;
    use HasIcon;
    use HasColor;

    public static function make(string $name): static
    {
        return app(static::class, ['name' => $name]);
    }
}

// Usage
MyComponent::make('action')
    ->label('Click Me')
    ->icon('plus')
    ->color('primary');
```

## Utilities

```php
use Laravilt\Support\Get;
use Laravilt\Support\Set;
use Laravilt\Support\Str;

// Dot notation access
Get::value($array, 'nested.key', 'default');
Set::value($array, 'nested.key', 'value');

// String utilities
Str::slug('My Component');  // 'my-component'
```

## Installation

```bash
composer require laravilt/support
```

## Generator Command

```bash
php artisan make:component RatingInput
```

## Documentation

- **[Complete Documentation](docs/index.md)** - Base components, concerns, utilities
- **[MCP Server Guide](docs/mcp-server.md)** - AI agent integration

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
