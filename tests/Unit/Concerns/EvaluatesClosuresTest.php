<?php

use Laravilt\Support\Concerns\EvaluatesClosures;

beforeEach(function () {
    $this->subject = new class
    {
        use EvaluatesClosures;

        public function evaluatePublic(mixed $value, array $parameters = []): mixed
        {
            return $this->evaluate($value, $parameters);
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

it('passes parameters to closures', function () {
    $closure = fn ($a, $b) => $a + $b;

    expect($this->subject->evaluatePublic($closure, [5, 3]))->toBe(8);
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
        ->and($this->subject->evaluatePublic($closure, ['custom']))->toBe('custom');
});
