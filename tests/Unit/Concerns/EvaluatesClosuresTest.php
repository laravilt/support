<?php

use Laravilt\Support\Concerns\EvaluatesClosures;
use Laravilt\Support\Utilities\Get;
use Laravilt\Support\Utilities\Set;

beforeEach(function () {
    $this->subject = new class
    {
        use EvaluatesClosures;

        public function evaluatePublic(mixed $value, array $parameters = []): mixed
        {
            return $this->evaluate($value, $parameters);
        }

        public function setContext(array $data, mixed $record = null): void
        {
            $this->evaluationContext($data, $record);
        }
    };
});

it('returns static values unchanged', function () {
    expect($this->subject->evaluatePublic('static string'))->toBe('static string')
        ->and($this->subject->evaluatePublic(42))->toBe(42)
        ->and($this->subject->evaluatePublic(true))->toBeTrue()
        ->and($this->subject->evaluatePublic(['array']))->toBe(['array']);
});

it('evaluates closures', function () {
    $closure = fn () => 'evaluated';

    expect($this->subject->evaluatePublic($closure))->toBe('evaluated');
});

it('passes named parameters to closures', function () {
    $closure = fn ($a, $b) => $a + $b;

    expect($this->subject->evaluatePublic($closure, ['a' => 5, 'b' => 3]))->toBe(8);
});

it('evaluates nested closures', function () {
    $outerClosure = fn () => fn () => 'nested';

    $result = $this->subject->evaluatePublic($outerClosure);

    expect($result)->toBeInstanceOf(Closure::class);
    expect($result())->toBe('nested');
});

it('handles closures with no parameters', function () {
    $closure = fn () => 'no params';

    expect($this->subject->evaluatePublic($closure, []))->toBe('no params');
});

it('handles closures with optional parameters', function () {
    $closure = fn ($a = 'default') => $a;

    expect($this->subject->evaluatePublic($closure, []))->toBe('default')
        ->and($this->subject->evaluatePublic($closure, ['a' => 'custom']))->toBe('custom');
});

it('injects Get utility when type-hinted', function () {
    $this->subject->setContext(['name' => 'John', 'age' => 30]);

    $closure = fn (Get $get) => $get('name');

    expect($this->subject->evaluatePublic($closure))->toBe('John');
});

it('injects Set utility when type-hinted', function () {
    $this->subject->setContext(['name' => 'John']);

    $closure = function (Set $set, Get $get) {
        $set('name', 'Jane');

        return $get('name');
    };

    $result = $this->subject->evaluatePublic($closure);

    // Verify Set modified the data that Get can read
    expect($result)->toBe('Jane');
});

it('passes data parameter by name', function () {
    $this->subject->setContext(['key' => 'value']);

    $closure = fn ($data) => $data['key'];

    expect($this->subject->evaluatePublic($closure))->toBe('value');
});

it('passes record parameter by name', function () {
    $record = (object) ['id' => 1, 'name' => 'Test'];
    $this->subject->setContext([], $record);

    $closure = fn ($record) => $record->name;

    expect($this->subject->evaluatePublic($closure))->toBe('Test');
});

it('supports mixed dependency injection', function () {
    $this->subject->setContext(['status' => 'active']);

    $closure = fn (Get $get, $data) => [
        'status' => $get('status'),
        'hasData' => ! empty($data),
    ];

    $result = $this->subject->evaluatePublic($closure);

    expect($result)->toBe([
        'status' => 'active',
        'hasData' => true,
    ]);
});

it('handles Get utility with nested data', function () {
    $this->subject->setContext([
        'user' => [
            'profile' => [
                'name' => 'John Doe',
            ],
        ],
    ]);

    $closure = fn (Get $get) => $get('user.profile.name');

    expect($this->subject->evaluatePublic($closure))->toBe('John Doe');
});

it('handles Set utility with nested data', function () {
    $this->subject->setContext(['user' => ['name' => 'John']]);

    $closure = function (Set $set, Get $get) {
        $set('user.email', 'john@example.com');

        return $get('user.email');
    };

    $result = $this->subject->evaluatePublic($closure);
    expect($result)->toBe('john@example.com');
});

it('uses default values when parameter not provided', function () {
    $closure = fn ($name = 'Guest', $role = 'User') => "$name ($role)";

    expect($this->subject->evaluatePublic($closure))->toBe('Guest (User)');
});

it('combines named parameters with defaults', function () {
    $closure = fn ($name = 'Guest', $role = 'User') => "$name ($role)";

    expect($this->subject->evaluatePublic($closure, ['name' => 'Admin']))
        ->toBe('Admin (User)');
});

it('passes null for unresolvable parameters', function () {
    $closure = fn ($unknown) => $unknown === null ? 'null received' : 'value received';

    expect($this->subject->evaluatePublic($closure))->toBe('null received');
});
