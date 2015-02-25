$router->any('my-any-route', [
	'uses' => 'App\Http\Controllers\AnyController@anyAnnotations',
	'as' => NULL,
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);
