# Laravilt Core

Laravilt Core is a complete SPA (Single Page Application) framework for Laravel and Vue 3. It provides a powerful, feature-rich foundation for building modern single-page applications with seamless navigation, modal stacks, toast notifications, state management, and more.

## Architecture

Laravilt Core consists of three integrated layers:

- **PHP Layer** - Server-side request handling, middleware, and response formatting
- **JavaScript Layer** - Complete SPA engine (Laravilt.js - 814 lines) with 30+ API methods
- **Vue Layer** - Root app component, plugin system, and reusable Vue components

## Package Structure

```
resources/js/
├── core/
│   ├── Laravilt.js          (814 lines) - Complete SPA engine
│   ├── LaraviltApp.vue      (325 lines) - Root Vue component
│   ├── LaraviltPlugin.js    (120 lines) - Vue 3 plugin installer
│   └── LaraviltProgress.js  (138 lines) - NProgress integration
├── components/
│   ├── ComponentRenderer.vue - Dynamic component renderer
│   ├── Link.vue             - SPA navigation link
│   ├── Modal.vue            - Modal/slideover component
│   ├── Render.vue           - Dynamic HTML renderer
│   └── ServerError.vue      - Error overlay
└── laravilt-core.js         - Main entry point with exports
```

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

## JavaScript Core (Laravilt.js)

The Laravilt.js engine provides a comprehensive SPA framework with 30+ API methods organized into logical groups:

### Navigation & Page Management

```javascript
import { Laravilt } from '@laravilt/support';

// Navigate to a new page
Laravilt.visit('/dashboard', {
    method: 'GET',
    data: {},
    headers: {},
    preserveScroll: false,
    preserveState: false,
    replace: false,
    only: [],
    onBefore: (visit) => {},
    onStart: (visit) => {},
    onProgress: (progress) => {},
    onSuccess: (page) => {},
    onError: (errors) => {},
    onFinish: (visit) => {},
});

// Replace current page (no history entry)
Laravilt.replace('/users', { method: 'GET', preserveScroll: true });

// Refresh current page
Laravilt.refresh({ preserveScroll: true });

// Replace URL without reload
Laravilt.replaceUrlOfCurrentPage('/users?page=2');

// Initialize Laravilt
Laravilt.init({
    initialHtml: '...',
    initialDynamics: {},
    maxKeepAlive: 10,
    transformAnchors: false,
});
```

### Modal & Slideover Stack

```javascript
// Open modal
Laravilt.modal('/users/create', {
    method: 'GET',
    data: {},
    headers: {},
    onBefore: (visit) => {},
    onSuccess: (page) => {},
    onError: (errors) => {},
});

// Open slideover
Laravilt.slideover('/notifications', {
    position: 'right', // 'left' or 'right'
    method: 'GET',
});

// Access modal stack (reactive)
import { watch } from 'vue';
watch(Laravilt.currentStack, (stack) => {
    console.log('Current modals:', stack);
});
```

### Toast Notifications

```javascript
// Push toast notification
Laravilt.pushToast({
    message: 'User created successfully',
    title: 'Success',
    type: 'success', // 'success', 'error', 'warning', 'info'
    dismissible: true,
    autoDismiss: true,
    duration: 3000,
});

// Dismiss specific toast
Laravilt.dismissToast('toast-id');

// Access toasts (reactive refs)
console.log(Laravilt.toasts.value);         // Array in order
console.log(Laravilt.toastsReversed.value); // Reversed array
```

### State Management

```javascript
// Remember state (persists across navigation)
Laravilt.remember('form-data', {
    name: 'John',
    email: 'john@example.com'
});

// Restore state
const formData = Laravilt.restore('form-data');
if (formData) {
    console.log('Restored:', formData);
}

// Forget state
Laravilt.forget('form-data');

// State persists in sessionStorage and survives:
// - Page navigation
// - Page refresh
// - Modal opens/closes
```

### Lazy Loading & Rehydration

```javascript
// Lazy load component (loads on demand)
Laravilt.lazy('/components/heavy-chart', '#chart-container', {
    method: 'GET',
    params: { dataset: 'sales' },
});

// Rehydrate component (updates existing component)
Laravilt.rehydrate('/components/table', '#table-container', {
    method: 'GET',
    params: { page: 2 },
});

// Get dynamic component HTML
const html = Laravilt.htmlForDynamicComponent('my-component');
```

### Confirm Dialogs

```javascript
// Show confirm modal
Laravilt.confirm({
    title: 'Delete User',
    message: 'Are you sure you want to delete this user?',
    confirmButton: 'Delete',
    confirmButtonColor: 'danger',
    cancelButton: 'Cancel',
    onConfirm: () => {
        // Delete logic
    },
    onCancel: () => {
        // Cancel logic
    },
});

// Clear confirm modal
Laravilt.clearConfirmModal();

// Access confirm state (reactive ref)
import { watch } from 'vue';
watch(Laravilt.confirmModal, (modal) => {
    if (modal) {
        console.log('Confirm modal shown:', modal.title);
    }
});
```

### Event System

```javascript
// Register event listener
const handleUpdate = (user) => {
    console.log('User updated:', user);
};
Laravilt.on('user:updated', handleUpdate);

// Emit event
Laravilt.emit('user:updated', { id: 1, name: 'John Doe' });

// Remove specific listener
Laravilt.off('user:updated', handleUpdate);

// Built-in events:
// - laravilt:internal:request
// - laravilt:internal:request-progress
// - laravilt:internal:request-response
// - laravilt:internal:request-error
```

### Data Access (Reactive Refs)

```javascript
// Access shared data from server (reactive)
import { watch } from 'vue';
watch(Laravilt.sharedData, (data) => {
    console.log('Shared data:', data);
});

// Access flash data (reactive)
if (Laravilt.flashData.value) {
    console.log('Flash message:', Laravilt.flashData.value);
}

// Validation errors (reactive)
if (Laravilt.hasValidationErrors.value) {
    const errors = Laravilt.validationErrors.value;
    console.log('Validation errors:', errors);
}

// Page visit ID (increments on each navigation)
console.log('Current visit ID:', Laravilt.pageVisitId.value);
```

### File Downloads

```javascript
// Download file from blob
const blob = new Blob(['Hello, World!'], { type: 'text/plain' });
Laravilt.downloadFromBlob(blob, 'hello.txt');

// Download from URL
Laravilt.downloadFromURL('/export/users', 'users.xlsx');

// Download triggers automatically on server responses with:
// - Content-Disposition: attachment header
```

### Request API

```javascript
// Make Laravilt request
Laravilt.request('/api/users', {
    method: 'POST',
    data: { name: 'John', email: 'john@example.com' },
    headers: { 'X-Custom': 'value' },
    preserveScroll: false,
    preserveState: false,
    replace: false,
    only: ['users'], // Only update specific props
    onBefore: (visit) => {
        console.log('Before request');
        return true; // Return false to cancel
    },
    onStart: (visit) => {
        console.log('Request started');
    },
    onProgress: (progress) => {
        console.log('Progress:', progress.percentage);
    },
    onSuccess: (page) => {
        console.log('Success:', page);
    },
    onError: (errors) => {
        console.log('Errors:', errors);
    },
    onFinish: (visit) => {
        console.log('Request finished');
    },
});
```

### SSR Support

```javascript
// Detect SSR mode
if (Laravilt.isSsr) {
    // Skip client-only logic
    console.log('Running in SSR mode');
}

// SSR is automatically detected and handled
// No special configuration needed
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

## Vue 3 Plugin

Laravilt Core is installed as a Vue 3 plugin with full configuration options:

### Installation

```javascript
import { createApp } from 'vue';
import LaraviltPlugin from '@laravilt/support';
import LaraviltApp from '@laravilt/support/LaraviltApp';

const app = createApp(LaraviltApp);

app.use(LaraviltPlugin, {
    // Maximum KeepAlive components cached (default: 10)
    max_keep_alive: 10,

    // Component name prefix (default: 'Laravilt')
    prefix: 'Laravilt',

    // Transform regular <a> tags to SPA links (default: false)
    transform_anchors: false,

    // Name of the link component (default: 'Link')
    link_component: 'Link',

    // Progress bar configuration (default: false)
    progress_bar: {
        delay: 250,        // Delay before showing (ms)
        color: '#4B5563',  // Progress bar color
        css: true,         // Inject default CSS
        spinner: false,    // Show spinner
    },

    // View Transitions API (default: false)
    view_transitions: true,

    // Suppress Vue compiler errors (default: false)
    suppress_compile_errors: false,

    // Register custom components globally
    components: {
        'MyCustomComponent': MyComponent,
        'AnotherComponent': AnotherComponent,
    },
});

app.mount('#app');
```

### Accessing Laravilt in Components

```vue
<script setup>
import { inject } from 'vue';

// Access Laravilt instance
const $laravilt = inject('$laravilt');

// Access options
const $laraviltOptions = inject('$laraviltOptions');

// Use Laravilt methods
function navigateToDashboard() {
    $laravilt.visit('/dashboard');
}
</script>

<template>
  <button @click="navigateToDashboard">Dashboard</button>
</template>
```

### Registered Components

The plugin automatically registers these components:

**With Prefix** (default: `Laravilt`):
- `<LaraviltComponentRenderer>` - Dynamic component renderer
- `<LaraviltLink>` - SPA navigation link
- `<LaraviltModal>` - Modal/slideover component
- `<LaraviltRender>` - Dynamic HTML renderer
- `<LaraviltServerError>` - Error overlay

**Without Prefix**:
- `<Link>` - Alias for LaraviltLink (configurable via `link_component`)

### LaraviltApp.vue

The root application component providing:

**Features**:
- KeepAlive page caching with configurable max
- Modal stack rendering with backdrop blur
- Server error overlay with iframe
- Meta tag management (title, description, etc.)
- View Transitions API support
- RTL/LTR direction handling

**Props**:
```vue
<LaraviltApp
    :initial-html="'<div>...</div>'"
    :initial-dynamics="{}"
/>
```

**Backdrop Blur**:
The app automatically applies backdrop blur when modals are open based on the modal stack depth.

### Progress Bar

The LaraviltProgress module integrates NProgress for visual feedback:

```javascript
import { LaraviltProgress } from '@laravilt/support';

// Initialize manually (or via plugin options)
LaraviltProgress.init({
    delay: 250,        // Delay before showing progress bar (ms)
    color: '#4B5563',  // Progress bar color
    css: true,         // Inject default CSS styles
    spinner: false,    // Show loading spinner
});

// Automatically tracks these events:
// - laravilt:internal:request (starts progress)
// - laravilt:internal:request-progress (updates progress)
// - laravilt:internal:request-response (completes progress)
// - laravilt:internal:request-error (completes progress)
```

### Vue Components Reference

#### Link.vue
SPA navigation link component:
```vue
<Link href="/dashboard" method="GET" :data="{}" :headers="{}" preserve-scroll>
    Dashboard
</Link>
```

#### Modal.vue
Modal and slideover component:
```vue
<Modal name="create-user" :open="isOpen" @close="isOpen = false">
    <form>...</form>
</Modal>
```

#### Render.vue
Dynamic HTML renderer with Vue component compilation:
```vue
<Render :html="'<div>{{ message }}</div>'" :passthrough="{ message: 'Hello' }" />
```

#### ComponentRenderer.vue
Renders dynamic components from server:
```vue
<ComponentRenderer name="MyComponent" :props="{ foo: 'bar' }" />
```

#### ServerError.vue
Error overlay showing server error pages in iframe:
```vue
<ServerError :html="errorHtml" @close="clearError" />
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
