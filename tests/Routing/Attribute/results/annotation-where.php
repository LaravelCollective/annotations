$router->any('/', [
	'uses' => 'App\Http\Controllers\Attributes\WhereController@whereAnnotations',
	'as' => NULL,
	'middleware' => [],
	'where' => ['key' => 'value'],
	'domain' => NULL,
]);

$router->get('{key}', [
	'uses' => 'App\Http\Controllers\Attributes\WhereController@getWhereAnnotations',
	'as' => NULL,
	'middleware' => [],
	'where' => ['key' => 'value'],
	'domain' => NULL,
]);

