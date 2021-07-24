$router->any('my-any-route', [
	'uses' => 'App\Http\Controllers\Attributes\AnyController@anyAnnotations',
	'as' => NULL,
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);

$router->get('child/{id}', [
	'uses' => 'App\Http\Controllers\Attributes\ChildController@childMethodAnnotations',
	'as' => NULL,
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);
