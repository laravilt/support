# Basic Component Example

This guide walks you through creating your first Laravilt component from scratch.

## Step 1: Create the Component Class

```php
<?php

namespace App\Components;

use Laravilt\Support\Component;

class CardComponent extends Component
{
    protected string $view = 'components.card';

    protected string $title = '';
    protected string $footer = '';
    protected bool $elevated = false;

    public function title(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function footer(string $footer): static
    {
        $this->footer = $footer;
        return $this;
    }

    public function elevated(bool $elevated = true): static
    {
        $this->elevated = $elevated;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getFooter(): string
    {
        return $this->footer;
    }

    public function isElevated(): bool
    {
        return $this->elevated;
    }

    public function toLaraviltProps(): array
    {
        return array_merge(parent::toLaraviltProps(), [
            'title' => $this->getTitle(),
            'footer' => $this->getFooter(),
            'elevated' => $this->isElevated(),
        ]);
    }
}
```

## Step 2: Create the Blade View

Create `resources/views/components/card.blade.php`:

```blade
<x-laravilt-component name="card" :data="$component->toLaraviltProps()">
    <div class="card" :class="{ 'elevated': elevated }">
        <div v-if="title" class="card-header">
            <h3>{{ title }}</h3>
        </div>
        <div class="card-body">
            <div v-html="state"></div>
        </div>
        <div v-if="footer" class="card-footer">
            {{ footer }}
        </div>
    </div>
</x-laravilt-component>
```

## Step 3: Use the Component

### In Controller

```php
use App\Components\CardComponent;

class DashboardController extends Controller
{
    public function index()
    {
        $card = CardComponent::make('welcome-card')
            ->title('Welcome!')
            ->state('Hello, ' . auth()->user()->name)
            ->footer('Last login: ' . auth()->user()->last_login_at)
            ->elevated();

        return view('dashboard', [
            'welcomeCard' => $card,
        ]);
    }
}
```

### In Blade Template

```blade
<div>
    {!! $welcomeCard->render() !!}
</div>
```

### As JSON API

```php
Route::get('/api/cards/welcome', function () {
    $card = CardComponent::make('welcome-card')
        ->title('Welcome!')
        ->state('Hello, ' . auth()->user()->name);

    return response()->json($card->toApiProps());
});
```

## Step 4: Add Styling

Create `resources/css/components/card.css`:

```css
.card {
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    overflow: hidden;
}

.card.elevated {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.card-header {
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
    background-color: #f9fafb;
}

.card-body {
    padding: 1rem;
}

.card-footer {
    padding: 1rem;
    border-top: 1px solid #e5e7eb;
    background-color: #f9fafb;
    font-size: 0.875rem;
    color: #6b7280;
}
```

## Step 5: Test the Component

```php
use App\Components\CardComponent;
use Tests\TestCase;

class CardComponentTest extends TestCase
{
    public function test_can_create_card()
    {
        $card = CardComponent::make('test-card')
            ->title('Test Title')
            ->state('Test Content')
            ->footer('Test Footer')
            ->elevated();

        $this->assertEquals('Test Title', $card->getTitle());
        $this->assertEquals('Test Content', $card->getState());
        $this->assertEquals('Test Footer', $card->getFooter());
        $this->assertTrue($card->isElevated());
    }

    public function test_serializes_correctly()
    {
        $card = CardComponent::make('test-card')
            ->title('Test')
            ->state('Content');

        $props = $card->toLaraviltProps();

        $this->assertArrayHasKey('title', $props);
        $this->assertArrayHasKey('elevated', $props);
        $this->assertEquals('Test', $props['title']);
    }
}
```

## Next Steps

- Add more properties (icon, color, size)
- Support closures for dynamic values
- Add click handlers
- Create variants (success, error, warning)
- Add animations

## See Also

- [Component Base Class](../component-base-class.md)
- [Custom Serialization Example](custom-serialization.md)
