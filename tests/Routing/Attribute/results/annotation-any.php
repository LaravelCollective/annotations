$router->any('my-any-route', [
	'uses' => 'App\Http\Controllers\Attributes\AnyController@anyAnnotations',
	'as' => NULL,
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);
