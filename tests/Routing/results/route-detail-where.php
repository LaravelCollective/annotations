<?php

return [
    [
        'verb' => 'any',
        'path' => '/',
        'uses' => 'App\\Http\\Controllers\\WhereController@whereAnnotations',
        'as' => null,
        'middleware' => [],
        'where' => ["key" => "value"],
        'domain' => null,
    ],
    [
        'verb' => 'get',
        'path' => '{key}',
        'uses' => 'App\\Http\\Controllers\\WhereController@getWhereAnnotations',
        'as' => null,
        'middleware' => [],
        'where' => ["key" => "value"],
        'domain' => null,
    ],
];

