<?php

use Laravilt\Support\Concerns\InteractsWithState;

beforeEach(function () {
    $this->subject = new class
    {
        use InteractsWithState;
    };
});

it('has no state by default', function () {
    expect($this->subject->hasState())->toBeFalse()
        ->and($this->subject->getState())->toBeNull();
});

it('can set and get state', function () {
    $this->subject->state('test value');

    expect($this->subject->getState())->toBe('test value')
        ->and($this->subject->hasState())->toBeTrue();
});

it('can set default state', function () {
    $this->subject->default('default value');

    expect($this->subject->getState())->toBe('default value');
});

it('state takes precedence over default', function () {
    $this->subject->default('default value');
    $this->subject->state('actual value');

    expect($this->subject->getState())->toBe('actual value');
});

it('can set state to various types', function () {
    $this->subject->state(42);
    expect($this->subject->getState())->toBe(42);

    $this->subject->state(['array']);
    expect($this->subject->getState())->toBe(['array']);

    $this->subject->state(true);
    expect($this->subject->getState())->toBeTrue();
});

it('can set state to null explicitly', function () {
    $this->subject->state('value');
    $this->subject->state(null);

    expect($this->subject->hasState())->toBeFalse();
});
