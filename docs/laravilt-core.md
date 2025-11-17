# Laravilt Core

Laravilt Core provides the SPA (Single Page Application) functionality for the Laravilt framework. It handles client-side routing, component mounting, and seamless page transitions without full page reloads.

## Architecture

Laravilt Core consists of:

- **PHP Layer** - Server-side request handling and response formatting
- **JavaScript Layer** - Client-side navigation and component mounting
- **Bridge Layer** - Communication between PHP and JavaScript

## PHP Components

### LaraviltCore Class

Main class for SPA functionality:

```php
use Laravilt\Support\LaraviltCore\LaraviltCore;

// Check if request is from Laravilt SPA
if (LaraviltCore::isLaraviltRequest()) {
    // Handle SPA request
}

// Generate component key
$key = LaraviltCore::generateKey();

// Register component
$data = LaraviltCore::component('my-component', ['prop' => 'value']);
```

### Middleware

Process Laravilt requests:

```php
// app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [
        \Laravilt\Support\LaraviltCore\Http\Middleware\LaraviltMiddleware::class,
    ],
];
```

### Controller

Base controller for Laravilt responses:

```php
use Laravilt\Support\LaraviltCore\Http\Controllers\LaraviltController;

class MyController extends LaraviltController
{
    public function index()
    {
        $html = view('dashboard')->render();
        return $this->laraviltResponse($html);
    }
}
```

## JavaScript Components

### Navigation

```javascript
// Programmatic navigation
window.Laravilt.navigate('/dashboard', 'GET');

// Listen for navigation events
window.addEventListener('laravilt:navigated', (e) => {
    console.log('Navigated to:', e.detail.url);
});
```

### Event Bus

```javascript
// Register event listener
window.Laravilt.eventBus.on('user:updated', (user) => {
    console.log('User updated:', user);
});

// Emit event
window.Laravilt.eventBus.emit('user:updated', { id: 1, name: 'John' });

// Remove listener
window.Laravilt.eventBus.off('user:updated', callback);
```

### Component Mounting

```javascript
// Components are auto-mounted on page load
// Manual remount after dynamic content
window.Laravilt.mountComponents();

// Unmount all components
window.Laravilt.unmountAll();
```

## Blade Components

### SPA Links

```blade
<x-laravilt-link href="/dashboard" method="GET">
    Dashboard
</x-laravilt-link>
```

### Modals

```blade
<x-laravilt-modal name="confirm-delete" :open="false" title="Confirm">
    Are you sure?
</x-laravilt-modal>
```

```javascript
// Open modal
window.Laravilt.eventBus.emit('modal:open', 'confirm-delete');

// Close modal
window.Laravilt.eventBus.emit('modal:close', 'confirm-delete');
```

### Generic Components

```blade
<x-laravilt-component name="my-component" :data="['key' => 'value']">
    <div>{{ key }}</div>
</x-laravilt-component>
```

## Vue Plugin

Use as a Vue plugin:

```javascript
import { createApp } from 'vue';
import LaraviltCore from '@laravilt/support';

const app = createApp({});
app.use(LaraviltCore);
app.mount('#app');
```

## Request Flow

1. User clicks a Laravilt link
2. JavaScript intercepts the click
3. Request sent with `X-Laravilt: true` header
4. Laravel processes the request
5. Laravilt Middleware detects SPA request
6. Response returned as JSON with HTML
7. JavaScript updates the DOM
8. Vue components remounted
9. Browser history updated

## Best Practices

### Use Laravilt Links

```blade
{{-- Good --}}
<x-laravilt-link href="/dashboard">Dashboard</x-laravilt-link>

{{-- Avoid --}}
<a href="/dashboard">Dashboard</a>
```

### Handle Errors

```javascript
window.addEventListener('laravilt:error', (e) => {
    console.error('Navigation error:', e.detail.error);
    // Show error message to user
});
```

### Clean Up Event Listeners

```javascript
const handler = (data) => console.log(data);

// Register
window.Laravilt.eventBus.on('event', handler);

// Cleanup
window.Laravilt.eventBus.off('event', handler);
```

## See Also

- [Component Base Class](component-base-class.md)
- [Serialization Guide](serialization.md)
