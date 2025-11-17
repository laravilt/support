<?php

use Laravilt\Support\Utilities\Translator;

test('detects arabic as rtl', function () {
    expect(Translator::isRTL('ar'))->toBeTrue();
});

test('detects hebrew as rtl', function () {
    expect(Translator::isRTL('he'))->toBeTrue();
});

test('detects persian as rtl', function () {
    expect(Translator::isRTL('fa'))->toBeTrue();
});

test('detects urdu as rtl', function () {
    expect(Translator::isRTL('ur'))->toBeTrue();
});

test('detects english as ltr', function () {
    expect(Translator::isRTL('en'))->toBeFalse();
});

test('detects french as ltr', function () {
    expect(Translator::isRTL('fr'))->toBeFalse();
});

test('gets rtl direction for arabic', function () {
    expect(Translator::direction('ar'))->toBe('rtl');
});

test('gets ltr direction for english', function () {
    expect(Translator::direction('en'))->toBe('ltr');
});

test('uses current app locale when no locale provided', function () {
    app()->setLocale('ar');

    expect(Translator::isRTL())->toBeTrue()
        ->and(Translator::direction())->toBe('rtl');
});

test('gets list of rtl locales', function () {
    $rtlLocales = Translator::getRTLLocales();

    expect($rtlLocales)->toBeArray()
        ->and($rtlLocales)->toContain('ar', 'he', 'fa', 'ur');
});

test('can add custom rtl locale', function () {
    Translator::addRTLLocale('custom');

    expect(Translator::getRTLLocales())->toContain('custom');
});
