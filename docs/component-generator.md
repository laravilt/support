# Component Generator

The Laravilt Support package includes an Artisan command to quickly scaffold new components.

## Command

```bash
php artisan laravilt:component {name} {--force}
```

### Arguments

- `name` - The name of the component (e.g., `Alert`, `Badge`, `Card`)

### Options

- `--force` - Overwrite existing component files if they exist

## Usage

### Basic Example

```bash
php artisan laravilt:component Alert
```

This creates:
- `app/Components/Alert.php` - Component class
- `resources/views/components/alert.blade.php` - View template

### Naming Conventions

The generator supports multiple naming formats:

```bash
# StudlyCase (recommended)
php artisan laravilt:component UserProfile
# Creates: UserProfile.php, user-profile.blade.php

# kebab-case
php artisan laravilt:component user-profile
# Creates: UserProfile.php, user-profile.blade.php

# snake_case
php artisan laravilt:component user_profile
# Creates: UserProfile.php, user-profile.blade.php
```

## Generated Files

### Component Class

The generated component extends `Laravilt\Support\Component` with:

**Default Properties:**
- `title` - Component title
- `description` - Component description

**Default Methods:**
- `title(string $title)` - Set title
- `description(string $description)` - Set description
- `getTitle()` - Get evaluated title
- `getDescription()` - Get evaluated description
- `toLaraviltProps()` - Serialize for Laravilt

**Example output:**

```php
<?php

namespace App\Components;

use Laravilt\Support\Component;

class Alert extends Component
{
    protected string $view = 'components.alert';

    protected string $title = '';
    protected string $description = '';

    public function title(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function description(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->evaluate($this->title);
    }

    public function getDescription(): string
    {
        return $this->evaluate($this->description);
    }

    public function toLaraviltProps(): array
    {
        return array_merge(parent::toLaraviltProps(), [
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
        ]);
    }
}
```

### View Template

The generated Blade view includes:

- Component wrapper with ID
- Label section
- Title and description rendering
- State rendering
- Helper text section

**Example output:**

```blade
<div class="laravilt-alert" data-component="{{ $component->getId() }}">
    @if ($component->getLabel())
        <label class="component-label">{{ $component->getLabel() }}</label>
    @endif

    <div class="component-content">
        @if ($component->getTitle())
            <h3>{{ $component->getTitle() }}</h3>
        @endif

        @if ($component->getDescription())
            <p>{{ $component->getDescription() }}</p>
        @endif

        <div class="component-state">
            {!! $component->getState() !!}
        </div>
    </div>

    @if ($component->getHelperText())
        <small class="helper-text">{{ $component->getHelperText() }}</small>
    @endif
</div>
```

## Customization

After generating, you can customize the component:

### 1. Add Custom Properties

```php
class Alert extends Component
{
    protected string $type = 'info'; // Add new property

    public function type(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function success(): static
    {
        return $this->type('success');
    }

    public function error(): static
    {
        return $this->type('error');
    }
}
```

### 2. Customize Serialization

```php
public function toLaraviltProps(): array
{
    return array_merge(parent::toLaraviltProps(), [
        'title' => $this->getTitle(),
        'description' => $this->getDescription(),
        'type' => $this->type,
        'icon' => $this->getIcon(),
        'color' => $this->getColor(),
    ]);
}
```

### 3. Update View Template

```blade
<div class="laravilt-alert alert-{{ $component->type }}">
    <!-- Custom markup -->
</div>
```

## Usage Examples

### In Controllers

```php
use App\Components\Alert;

public function index()
{
    $alert = Alert::make('welcome-alert')
        ->title('Welcome!')
        ->description('Thanks for using Laravilt')
        ->label('System Message');

    return view('dashboard', ['alert' => $alert]);
}
```

### In Blade

```blade
{!! $alert->render() !!}
```

### As API Response

```php
return response()->json([
    'alert' => $alert->toApiProps(),
]);
```

## Overwriting Files

Use the `--force` flag to overwrite existing files:

```bash
php artisan laravilt:component Alert --force
```

This will:
- Overwrite `app/Components/Alert.php`
- Overwrite `resources/views/components/alert.blade.php`

**Warning:** This will delete any custom code you've added to these files.

## Directory Structure

The generator creates these paths:

```
app/
└── Components/
    └── Alert.php

resources/
└── views/
    └── components/
        └── alert.blade.php
```

If the directories don't exist, they will be created automatically.

## Best Practices

1. **Use descriptive names**: `NotificationBadge` instead of `Badge1`
2. **Follow conventions**: Use StudlyCase for component names
3. **Customize after generation**: The scaffold is a starting point
4. **Add type hints**: Specify return types for custom methods
5. **Document properties**: Add PHPDoc blocks for properties
6. **Test thoroughly**: Write tests for custom behavior

## See Also

- [Component Base Class](component-base-class.md)
- [Basic Component Example](examples/basic-component.md)
- [Custom Serialization](examples/custom-serialization.md)
