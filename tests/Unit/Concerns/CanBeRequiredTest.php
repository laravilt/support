<?php

use Laravilt\Support\Concerns\CanBeRequired;
use Laravilt\Support\Concerns\EvaluatesClosures;

beforeEach(function () {
    $this->subject = new class
    {
        use CanBeRequired;
        use EvaluatesClosures;
    };
});

it('is not required by default', function () {
    expect($this->subject->isRequired())->toBeFalse();
});

it('can be required', function () {
    $this->subject->required();

    expect($this->subject->isRequired())->toBeTrue();
});

it('can be conditionally required', function () {
    $this->subject->required(true);
    expect($this->subject->isRequired())->toBeTrue();

    $this->subject->required(false);
    expect($this->subject->isRequired())->toBeFalse();
});

it('evaluates closure for required condition', function () {
    $shouldBeRequired = false;
    $this->subject->required(function () use (&$shouldBeRequired) {
        return $shouldBeRequired;
    });

    expect($this->subject->isRequired())->toBeFalse();

    $shouldBeRequired = true;
    expect($this->subject->isRequired())->toBeTrue();
});
