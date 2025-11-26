<?php

use Laravilt\Support\Concerns\CanBeDisabled;
use Laravilt\Support\Concerns\EvaluatesClosures;

beforeEach(function () {
    $this->subject = new class
    {
        use CanBeDisabled;
        use EvaluatesClosures;
    };
});

it('is enabled by default', function () {
    expect($this->subject->isEnabled())->toBeTrue()
        ->and($this->subject->isDisabled())->toBeFalse();
});

it('can be disabled', function () {
    $this->subject->disabled();

    expect($this->subject->isDisabled())->toBeTrue()
        ->and($this->subject->isEnabled())->toBeFalse();
});

it('can be conditionally disabled', function () {
    $this->subject->disabled(true);
    expect($this->subject->isDisabled())->toBeTrue();

    $this->subject->disabled(false);
    expect($this->subject->isDisabled())->toBeFalse();
});

it('evaluates closure for disabled condition', function () {
    $shouldDisable = false;
    $this->subject->disabled(function () use (&$shouldDisable) {
        return $shouldDisable;
    });

    expect($this->subject->isDisabled())->toBeFalse();

    $shouldDisable = true;
    expect($this->subject->isDisabled())->toBeTrue();
});
