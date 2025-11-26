<?php

use Laravilt\Support\Concerns\CanBeReadonly;
use Laravilt\Support\Concerns\EvaluatesClosures;

beforeEach(function () {
    $this->subject = new class
    {
        use CanBeReadonly;
        use EvaluatesClosures;
    };
});

it('is not readonly by default', function () {
    expect($this->subject->isReadonly())->toBeFalse();
});

it('can be readonly', function () {
    $this->subject->readonly();

    expect($this->subject->isReadonly())->toBeTrue();
});

it('can be conditionally readonly', function () {
    $this->subject->readonly(true);
    expect($this->subject->isReadonly())->toBeTrue();

    $this->subject->readonly(false);
    expect($this->subject->isReadonly())->toBeFalse();
});

it('evaluates closure for readonly condition', function () {
    $shouldBeReadonly = false;
    $this->subject->readonly(function () use (&$shouldBeReadonly) {
        return $shouldBeReadonly;
    });

    expect($this->subject->isReadonly())->toBeFalse();

    $shouldBeReadonly = true;
    expect($this->subject->isReadonly())->toBeTrue();
});
