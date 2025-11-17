<?php

use Laravilt\Support\Utilities\Get;

test('can get value from array with direct key', function () {
    $data = ['name' => 'John'];

    expect(Get::value($data, 'name'))->toBe('John');
});

test('can get value from nested array with dot notation', function () {
    $data = [
        'user' => [
            'name' => 'John',
            'email' => 'john@example.com',
        ],
    ];

    expect(Get::value($data, 'user.name'))->toBe('John')
        ->and(Get::value($data, 'user.email'))->toBe('john@example.com');
});

test('can get value from deeply nested array', function () {
    $data = [
        'company' => [
            'department' => [
                'team' => [
                    'leader' => 'Alice',
                ],
            ],
        ],
    ];

    expect(Get::value($data, 'company.department.team.leader'))->toBe('Alice');
});

test('returns default when key does not exist', function () {
    $data = ['name' => 'John'];

    expect(Get::value($data, 'email', 'default'))->toBe('default');
});

test('returns null when key does not exist and no default', function () {
    $data = ['name' => 'John'];

    expect(Get::value($data, 'email'))->toBeNull();
});

test('returns default for nested non-existent key', function () {
    $data = ['user' => ['name' => 'John']];

    expect(Get::value($data, 'user.email', 'none'))->toBe('none');
});
