<?php

use Laravilt\Support\Frontend;

function frontendPackageJson(array $contents): string
{
    $path = tempnam(sys_get_temp_dir(), 'laravilt-package-json');
    file_put_contents($path, json_encode($contents));

    return $path;
}

it('detects react from package.json', function () {
    $path = frontendPackageJson(['dependencies' => ['react' => '^19.2.0', '@inertiajs/react' => '^3.0.0']]);

    expect(Frontend::detect($path))->toBe(Frontend::REACT);
});

it('detects vue from package.json', function () {
    $path = frontendPackageJson(['dependencies' => ['vue' => '^3.5.13', '@inertiajs/vue3' => '^3.0.0']]);

    expect(Frontend::detect($path))->toBe(Frontend::VUE);
});

it('prefers vue when both stacks are present', function () {
    $path = frontendPackageJson(['dependencies' => ['vue' => '^3.5.13'], 'devDependencies' => ['react' => '^19.2.0']]);

    expect(Frontend::detect($path))->toBe(Frontend::VUE);
});

it('falls back to vue without a readable package.json', function () {
    expect(Frontend::detect(sys_get_temp_dir().'/missing-laravilt-package.json'))->toBe(Frontend::VUE);

    $invalid = tempnam(sys_get_temp_dir(), 'laravilt-package-json');
    file_put_contents($invalid, 'not json');

    expect(Frontend::detect($invalid))->toBe(Frontend::VUE);
});

it('uses the configured stack before detection', function () {
    config(['laravilt-support.frontend' => 'REACT']);

    expect(Frontend::stack())->toBe(Frontend::REACT)
        ->and(Frontend::isReact())->toBeTrue()
        ->and(Frontend::isVue())->toBeFalse()
        ->and(Frontend::resourceDirectory())->toBe('react');

    config(['laravilt-support.frontend' => 'vue']);

    expect(Frontend::stack())->toBe(Frontend::VUE)
        ->and(Frontend::resourceDirectory())->toBe('js');
});

it('ignores an invalid configured stack', function () {
    config(['laravilt-support.frontend' => 'svelte']);

    expect(Frontend::isValid('svelte'))->toBeFalse()
        ->and(Frontend::stack())->toBeIn(Frontend::STACKS);
});

it('maps stacks to resource directories', function () {
    expect(Frontend::resourceDirectory(Frontend::REACT))->toBe('react')
        ->and(Frontend::resourceDirectory(Frontend::VUE))->toBe('js');
});
