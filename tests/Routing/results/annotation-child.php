$router->any('my-any-route', [
	'uses' => 'App\Http\Controllers\AnyController@anyAnnotations',
	'as' => NULL,
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);

$router->get('child/{id}', [
	'uses' => 'App\Http\Controllers\ChildController@childMethodAnnotations',
	'as' => NULL,
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);
