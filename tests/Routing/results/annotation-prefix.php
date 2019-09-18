$router->get('any-prefix/route-with-prefix', [
	'uses' => 'App\Http\Controllers\PrefixController@withPrefixAnnotations',
	'as' => 'route.with.prefix',
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);

$router->get('route-without-prefix', [
	'uses' => 'App\Http\Controllers\PrefixController@withoutPrefixAnnotations',
	'as' => 'route.without.prefix',
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);