<?php

return [
    [
        'verb' => 'any',
        'path' => '/',
        'uses' => 'App\\Http\\Controllers\\Attributes\\WhereController@whereAnnotations',
        'as' => null,
        'middleware' => [],
        'where' => ["key" => "value"],
        'domain' => null,
    ],
    [
        'verb' => 'get',
        'path' => '{key}',
        'uses' => 'App\\Http\\Controllers\\Attributes\\WhereController@getWhereAnnotations',
        'as' => null,
        'middleware' => [],
        'where' => ["key" => "value"],
        'domain' => null,
    ],
];

