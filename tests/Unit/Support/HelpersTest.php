<?php

test('array-filter-recursive', function () {
    $array = [
        'name' => 'John Doe',
        'email' => 'johndoe@mail.com',
        'password' => '',
        'roles' => [
            'admin' => 'Admin',
            'editor' => '',
            'viewer' => 'Viewer',
        ],
        'permissions' => [
            'create' => 'Create',
            'read' => '',
            'update' => 'Update',
            'delete' => '',
        ],
    ];

    $filtered = array_filter_recursive($array);

    expect($filtered)->toBe([
        'name' => 'John Doe',
        'email' => 'johndoe@mail.com',
        'roles' => [
            'admin' => 'Admin',
            'viewer' => 'Viewer',
        ],
        'permissions' => [
            'create' => 'Create',
            'update' => 'Update',
        ],
    ]);
});

test('get-blog-owner-name', function () {
    $locale = 'hu';
    $name = get_blog_owner_name($locale);
    expect($name)->toBe('Tar Gergő');

    $locale = 'en';
    $name = get_blog_owner_name($locale);
    expect($name)->toBe('Gergő Tar');
});

test('prefix-table-columns', function () {
    $table = 'users';
    $columns = ['id', 'name', 'email'];

    $prefixed = prefix_table_columns($table, $columns);

    expect($prefixed)->toBe([
        'users.id',
        'users.name',
        'users.email',
    ]);
});
