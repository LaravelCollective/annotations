$router->any('/', [
	'uses' => 'App\Http\Controllers\WhereController@whereAnnotations',
	'as' => NULL,
	'middleware' => [],
	'where' => ['key' => 'value'],
	'domain' => NULL,
]);

$router->get('{key}', [
	'uses' => 'App\Http\Controllers\WhereController@getWhereAnnotations',
	'as' => NULL,
	'middleware' => [],
	'where' => ['key' => 'value'],
	'domain' => NULL,
]);

