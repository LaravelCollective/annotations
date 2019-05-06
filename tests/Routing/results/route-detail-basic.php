<?php

return [
    [
        'verb' => 'put',
        'path' => 'more/{id}',
        'uses' => 'App\\Http\\Controllers\\BasicController@doMore',
        'as' => null,
        'middleware' =>
            [
                0 => 'FooMiddleware',
                1 => 'BarMiddleware',
                2 => 'QuxMiddleware',
            ],
        'where' =>
            [
                'id' => 'regex',
            ],
        'domain' => '{id}.account.com',
    ],
    [
        'label' => 'foobar/photos@index',
        'middleware' =>
            [
                0 => 'FooMiddleware',
                1 => 'BarMiddleware',
                2 => 'BoomMiddleware',
                3 => 'BazMiddleware',
            ],
        'prefix' => null,
        'where' =>
            [
                'id' => 'regex',
            ],
        'domain' => '{id}.account.com',
        'name' => 'foobar/photos',
        'resource' => 'App\\Http\\Controllers\\BasicController',
        'method' => 'index',
        'names' =>
            [
                'index' => 'index.name',
            ],
    ],
    [
        'label' => 'foobar/photos@update',
        'middleware' =>
            [
                0 => 'FooMiddleware',
                1 => 'BarMiddleware',
            ],
        'prefix' => null,
        'where' =>
            [
                'id' => 'regex',
            ],
        'domain' => '{id}.account.com',
        'name' => 'foobar/photos',
        'resource' => 'App\\Http\\Controllers\\BasicController',
        'method' => 'update',
        'names' => [],
    ],
];
