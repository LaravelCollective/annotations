$router->get('any-prefix/route-with-prefix', [
	'uses' => 'App\Http\Controllers\Attributes\PrefixController@withPrefixAnnotations',
	'as' => 'route.with.prefix',
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);

$router->get('route-without-prefix', [
	'uses' => 'App\Http\Controllers\Attributes\PrefixController@withoutPrefixAnnotations',
	'as' => 'route.without.prefix',
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);