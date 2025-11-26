<?php

use Laravilt\Support\Concerns\EvaluatesClosures;
use Laravilt\Support\Concerns\HasVisibility;

beforeEach(function () {
    $this->subject = new class
    {
        use EvaluatesClosures;
        use HasVisibility;
    };
});

it('is visible by default', function () {
    expect($this->subject->isVisible())->toBeTrue()
        ->and($this->subject->isHidden())->toBeFalse();
});

it('can be hidden', function () {
    $this->subject->hidden();

    expect($this->subject->isHidden())->toBeTrue()
        ->and($this->subject->isVisible())->toBeFalse();
});

it('can be shown', function () {
    $this->subject->hidden();
    $this->subject->visible();

    expect($this->subject->isVisible())->toBeTrue()
        ->and($this->subject->isHidden())->toBeFalse();
});

it('can be conditionally hidden', function () {
    $this->subject->hidden(true);
    expect($this->subject->isHidden())->toBeTrue();

    $this->subject->hidden(false);
    expect($this->subject->isHidden())->toBeFalse();
});

it('can be conditionally visible', function () {
    $this->subject->visible(true);
    expect($this->subject->isVisible())->toBeTrue();

    $this->subject->visible(false);
    expect($this->subject->isVisible())->toBeFalse();
});

it('evaluates closure for hidden condition', function () {
    $shouldHide = false;
    $this->subject->hidden(function () use (&$shouldHide) {
        return $shouldHide;
    });

    expect($this->subject->isHidden())->toBeFalse();

    $shouldHide = true;
    expect($this->subject->isHidden())->toBeTrue();
});

it('evaluates closure for visible condition', function () {
    $shouldShow = true;
    $this->subject->visible(function () use (&$shouldShow) {
        return $shouldShow;
    });

    expect($this->subject->isVisible())->toBeTrue();

    $shouldShow = false;
    expect($this->subject->isVisible())->toBeFalse();
});

it('hidden takes precedence over visible', function () {
    $this->subject->visible(true);
    $this->subject->hidden(true);

    expect($this->subject->isHidden())->toBeTrue();
});
