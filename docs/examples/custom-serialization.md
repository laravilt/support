# Custom Serialization Example

Learn how to customize component serialization for different platforms.

## Scenario

You're building a notification component that needs to work across:
- Laravilt (Blade + Vue) - Full featured UI
- REST API - Mobile app consumption
- Flutter - Native mobile widgets

## Step 1: Create the Component

```php
<?php

namespace App\Components;

use Laravilt\Support\Component;

class NotificationComponent extends Component
{
    protected string $view = 'components.notification';

    protected string $type = 'info'; // info, success, warning, error
    protected string $title = '';
    protected string $message = '';
    protected ?string $actionUrl = null;
    protected ?string $actionLabel = null;
    protected ?\DateTimeInterface $timestamp = null;
    protected bool $dismissible = true;

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

    public function warning(): static
    {
        return $this->type('warning');
    }

    public function title(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function message(string $message): static
    {
        $this->message = $message;
        return $this;
    }

    public function action(string $url, string $label): static
    {
        $this->actionUrl = $url;
        $this->actionLabel = $label;
        return $this;
    }

    public function timestamp(\DateTimeInterface $timestamp): static
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    public function dismissible(bool $dismissible = true): static
    {
        $this->dismissible = $dismissible;
        return $this;
    }

    // Laravilt serialization - Full featured
    public function toLaraviltProps(): array
    {
        return array_merge(parent::toLaraviltProps(), [
            'type' => $this->type,
            'title' => $this->title,
            'message' => $this->message,
            'actionUrl' => $this->actionUrl,
            'actionLabel' => $this->actionLabel,
            'timestamp' => $this->timestamp?->toIso8601String(),
            'timeAgo' => $this->timestamp?->diffForHumans(),
            'dismissible' => $this->dismissible,
            'icon' => $this->getIcon(),
            'color' => $this->getColor(),
        ]);
    }

    // API serialization - Minimal for bandwidth
    public function toApiProps(): array
    {
        return [
            'id' => $this->getId(),
            'type' => $this->type,
            'title' => $this->title,
            'message' => $this->message,
            'action' => $this->actionUrl ? [
                'url' => $this->actionUrl,
                'label' => $this->actionLabel,
            ] : null,
            'timestamp' => $this->timestamp?->timestamp,
            'dismissible' => $this->dismissible,
        ];
    }

    // Flutter serialization - Platform specific
    public function toFlutterProps(): array
    {
        return [
            'id' => $this->getId(),
            'notificationType' => $this->getFlutterType(),
            'title' => $this->title,
            'message' => $this->message,
            'actionUrl' => $this->actionUrl,
            'actionText' => $this->actionLabel,
            'timestampMillis' => $this->timestamp?->getTimestamp() * 1000,
            'isDismissible' => $this->dismissible,
            'iconData' => $this->getFlutterIconData(),
            'colorValue' => $this->getFlutterColorValue(),
        ];
    }

    protected function getIcon(): string
    {
        return match ($this->type) {
            'success' => '<svg>...</svg>',
            'error' => '<svg>...</svg>',
            'warning' => '<svg>...</svg>',
            default => '<svg>...</svg>',
        };
    }

    protected function getColor(): string
    {
        return match ($this->type) {
            'success' => 'green',
            'error' => 'red',
            'warning' => 'yellow',
            default => 'blue',
        };
    }

    protected function getFlutterType(): string
    {
        return match ($this->type) {
            'success' => 'NotificationType.success',
            'error' => 'NotificationType.error',
            'warning' => 'NotificationType.warning',
            default => 'NotificationType.info',
        };
    }

    protected function getFlutterIconData(): string
    {
        return match ($this->type) {
            'success' => 'Icons.check_circle',
            'error' => 'Icons.error',
            'warning' => 'Icons.warning',
            default => 'Icons.info',
        };
    }

    protected function getFlutterColorValue(): int
    {
        return match ($this->type) {
            'success' => 0xFF4CAF50, // Green
            'error' => 0xFFF44336,   // Red
            'warning' => 0xFFFF9800,  // Orange
            default => 0xFF2196F3,    // Blue
        };
    }
}
```

## Step 2: Use in Different Contexts

### Laravilt (Web)

```php
// Controller
$notification = NotificationComponent::make('welcome')
    ->success()
    ->title('Welcome!')
    ->message('Your account has been created successfully.')
    ->action('/dashboard', 'Go to Dashboard')
    ->timestamp(now())
    ->dismissible();

return view('dashboard', ['notification' => $notification]);
```

```blade
{{-- Blade --}}
{!! $notification->render() !!}
```

### REST API

```php
// API Controller
Route::get('/api/notifications', function () {
    $notifications = [
        NotificationComponent::make('notif-1')
            ->success()
            ->title('Upload Complete')
            ->message('Your file has been uploaded.')
            ->timestamp(now()->subMinutes(5)),

        NotificationComponent::make('notif-2')
            ->warning()
            ->title('Storage Almost Full')
            ->message('You have used 95% of your storage.')
            ->action('/settings/storage', 'Upgrade')
            ->timestamp(now()->subHour()),
    ];

    return response()->json([
        'notifications' => array_map(
            fn($n) => $n->toApiProps(),
            $notifications
        ),
    ]);
});
```

### Flutter Endpoint

```php
// Flutter API Controller
Route::get('/flutter/notifications', function () {
    $notifications = Notification::all();

    return response()->json([
        'notifications' => $notifications->map(function ($notification) {
            return NotificationComponent::make($notification->id)
                ->type($notification->type)
                ->title($notification->title)
                ->message($notification->message)
                ->timestamp($notification->created_at)
                ->toFlutterProps();
        }),
    ]);
});
```

## Step 3: Platform-Specific Handling

### Vue Component (Laravilt)

```vue
<template>
    <div :class="['notification', `notification-${type}`]">
        <div class="notification-icon" v-html="icon"></div>
        <div class="notification-content">
            <h4>{{ title }}</h4>
            <p>{{ message }}</p>
            <small v-if="timeAgo">{{ timeAgo }}</small>
        </div>
        <a v-if="actionUrl" :href="actionUrl" class="notification-action">
            {{ actionLabel }}
        </a>
        <button v-if="dismissible" @click="dismiss" class="notification-close">
            ×
        </button>
    </div>
</template>
```

### Mobile App (React Native / Flutter)

```dart
// Flutter Widget
class NotificationWidget extends StatelessWidget {
  final Map<String, dynamic> props;

  NotificationWidget(this.props);

  @override
  Widget build(BuildContext context) {
    return Card(
      color: Color(props['colorValue']),
      child: ListTile(
        leading: Icon(props['iconData']),
        title: Text(props['title']),
        subtitle: Text(props['message']),
        trailing: props['isDismissible']
            ? IconButton(icon: Icon(Icons.close), onPressed: () {})
            : null,
      ),
    );
  }
}
```

## Benefits

1. **Single Source of Truth** - One component class for all platforms
2. **Type Safety** - Platform-specific data types
3. **Optimized Payloads** - Each platform gets only what it needs
4. **Maintainability** - Changes in one place propagate everywhere

## See Also

- [Serialization Guide](../serialization.md)
- [Basic Component Example](basic-component.md)
- [Component Base Class](../component-base-class.md)
