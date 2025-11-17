# Utilities

Laravilt Support includes several utility classes to help with common tasks.

## Get - Dot Notation Array Access

Safely access nested array values using dot notation.

### Usage

```php
use Laravilt\Support\Utilities\Get;

$data = [
    'user' => [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'address' => [
            'city' => 'New York',
            'country' => 'USA',
        ],
    ],
];

// Get nested value
$name = Get::value($data, 'user.name');
// Returns: 'John Doe'

// Deep nested value
$city = Get::value($data, 'user.address.city');
// Returns: 'New York'

// With default value
$phone = Get::value($data, 'user.phone', 'N/A');
// Returns: 'N/A'
```

### API

```php
Get::value(array $data, string $key, mixed $default = null): mixed
```

## Set - Dot Notation Array Manipulation

Set nested array values using dot notation.

### Usage

```php
use Laravilt\Support\Utilities\Set;

$data = [];

// Set nested value
Set::value($data, 'user.name', 'John Doe');
Set::value($data, 'user.email', 'john@example.com');

// Result:
// [
//     'user' => [
//         'name' => 'John Doe',
//         'email' => 'john@example.com',
//     ],
// ]

// Deep nested value
Set::value($data, 'user.address.city', 'New York');

// Result:
// [
//     'user' => [
//         'name' => 'John Doe',
//         'email' => 'john@example.com',
//         'address' => [
//             'city' => 'New York',
//         ],
//     ],
// ]
```

### API

```php
Set::value(array &$data, string $key, mixed $value): array
```

## Translator - RTL Detection & Helpers

Detect RTL (right-to-left) languages and manage translations.

### RTL Detection

```php
use Laravilt\Support\Utilities\Translator;

// Check if locale is RTL
Translator::isRTL('ar'); // true
Translator::isRTL('he'); // true (Hebrew)
Translator::isRTL('fa'); // true (Persian)
Translator::isRTL('ur'); // true (Urdu)
Translator::isRTL('en'); // false

// Use current app locale
app()->setLocale('ar');
Translator::isRTL(); // true
```

### Text Direction

```php
// Get text direction
Translator::direction('ar'); // 'rtl'
Translator::direction('en'); // 'ltr'

// Use in HTML
<html dir="{{ \Laravilt\Support\Utilities\Translator::direction() }}">
```

### RTL Locales Management

```php
// Get all RTL locales
$locales = Translator::getRTLLocales();
// Returns: ['ar', 'he', 'fa', 'ur', 'yi', 'ji', 'iw']

// Add custom RTL locale
Translator::addRTLLocale('ps'); // Pashto
```

### API

```php
Translator::isRTL(?string $locale = null): bool
Translator::direction(?string $locale = null): string
Translator::getRTLLocales(): array
Translator::addRTLLocale(string $locale): void
```

## Practical Examples

### Building Dynamic Forms

```php
use Laravilt\Support\Utilities\Get;
use Laravilt\Support\Utilities\Set;

// Get form data
$formData = request()->all();
$userName = Get::value($formData, 'user.profile.name');

// Build response
$response = [];
Set::value($response, 'user.profile.name', $userName);
Set::value($response, 'user.profile.updated_at', now());
```

### Multilingual Components

```php
use Laravilt\Support\Utilities\Translator;

class MyComponent extends Component
{
    protected function setUp(): void
    {
        // Auto-detect RTL
        if (Translator::isRTL()) {
            $this->meta(['textAlign' => 'right']);
        }
    }
}
```

### Localized Views

```blade
<div dir="{{ \Laravilt\Support\Utilities\Translator::direction() }}">
    @if(\Laravilt\Support\Utilities\Translator::isRTL())
        <style>
            .text-align { text-align: right; }
        </style>
    @endif
</div>
```

## See Also

- [Component Base Class](component-base-class.md)
- [Basic Component Example](examples/basic-component.md)
