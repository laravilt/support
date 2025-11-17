<?php

use Laravilt\Support\Utilities\Set;

test('can set value in array with direct key', function () {
    $data = [];
    Set::value($data, 'name', 'John');

    expect($data)->toBe(['name' => 'John']);
});

test('can set value in nested array with dot notation', function () {
    $data = [];
    Set::value($data, 'user.name', 'John');

    expect($data)->toBe([
        'user' => [
            'name' => 'John',
        ],
    ]);
});

test('can set value in deeply nested array', function () {
    $data = [];
    Set::value($data, 'company.department.team.leader', 'Alice');

    expect($data)->toBe([
        'company' => [
            'department' => [
                'team' => [
                    'leader' => 'Alice',
                ],
            ],
        ],
    ]);
});

test('can update existing value', function () {
    $data = ['name' => 'John'];
    Set::value($data, 'name', 'Jane');

    expect($data)->toBe(['name' => 'Jane']);
});

test('can set multiple nested values', function () {
    $data = [];
    Set::value($data, 'user.name', 'John');
    Set::value($data, 'user.email', 'john@example.com');

    expect($data)->toBe([
        'user' => [
            'name' => 'John',
            'email' => 'john@example.com',
        ],
    ]);
});
