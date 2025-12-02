# MCP Server Integration

The Laravilt Support package can be integrated with MCP (Model Context Protocol) server for AI agent interaction.

## Available Generator Command

### make:component
Generate a custom component class.

**Usage:**
```bash
php artisan make:component RatingInput
php artisan make:component Forms/RatingInput
php artisan make:component RatingInput --force
```

**Arguments:**
- `name` (string, required): Component class name (StudlyCase)

**Options:**
- `--force`: Overwrite existing file

**Generated Structure:**
```php
<?php

namespace App\Components;

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

    protected string $view = 'components.rating-input';

    protected function setUp(): void
    {
        parent::setUp();
        // Initialize your component here
    }

    public function toLaraviltProps(): array
    {
        return array_merge(parent::toLaraviltProps(), [
            // Add your component properties here
        ]);
    }
}
```

## Available Concerns (Traits)

For MCP tools to provide concern information:

- **CanBeDisabled**: Add disabled state to components
- **CanBeHidden**: Add hidden state to components
- **CanBeReadonly**: Add readonly state to components
- **CanBeRequired**: Add required state to components
- **EvaluatesClosures**: Evaluate closures with dependency injection
- **HasColumnSpan**: Grid column spanning support
- **HasHelperText**: Helper text support
- **HasId**: Unique identifier support
- **HasLabel**: Label and description support
- **HasPlaceholder**: Placeholder text support
- **HasState**: Component state management
- **HasVisibility**: Conditional visibility support
- **InteractsWithState**: State interaction utilities

## Available Utilities

For MCP tools to provide utility information:

- **Get**: Retrieve field values from component state
- **Set**: Update field values in component state
- **Str**: String manipulation helpers
- **Arr**: Array manipulation helpers

## Available Contracts

For MCP tools to provide contract information:

- **Buildable**: Interface for components with factory pattern
- **Serializable**: Interface for multi-platform serialization
- **InertiaSerializable**: Interface for Inertia.js serialization
- **HasSchema**: Interface for components with nested schemas

## Integration Example

MCP server tools should provide:

1. **list-components** - List all custom component classes
2. **component-info** - Get details about a specific component
3. **generate-component** - Generate a new custom component
4. **list-concerns** - List all available concerns (traits)
5. **list-utilities** - List all available utility classes
6. **list-contracts** - List all available contracts (interfaces)

## Security

The MCP server runs with the same permissions as your Laravel application. Ensure:
- Proper file permissions on the app/Components directory
- Secure configuration of the MCP server
- Limited access to the MCP configuration file
