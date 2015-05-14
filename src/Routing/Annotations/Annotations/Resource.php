<?php namespace Collective\Annotations\Routing\Annotations\Annotations;

use ReflectionClass;
use Illuminate\Support\Collection;
use Collective\Annotations\Routing\Annotations\MethodEndpoint;
use Collective\Annotations\Routing\Annotations\ResourceEndpoint;
use Collective\Annotations\Routing\Annotations\EndpointCollection;

/**
 * @Annotation
 */
class Resource extends Annotation {

	/**
	 * All of the resource controller methods.
	 *
	 * @var array
	 */
	protected $methods = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];

	/**
	 * {@inheritdoc}
	 */
	public function modifyCollection(EndpointCollection $endpoints, ReflectionClass $class)
	{
		$endpoints->push(new ResourceEndpoint([
			'reflection' => $class, 'name' => $this->value, 'names' => (array) $this->names,
			'only' => (array) $this->only, 'except' => (array) $this->except,
			'middleware' => $this->getMiddleware($endpoints),
			'domain' => $this->domain,
			'prefix' => $this->prefix
		]));
	}

	/**
	 * Get all of the middleware defined on the resource method endpoints.
	 *
	 * @param  \Collective\Annotations\Routing\Annotations\EndpointCollection  $endpoints
	 * @return array
	 */
	protected function getMiddleware(EndpointCollection $endpoints)
	{
		return $this->extractFromEndpoints($endpoints, 'middleware');
	}

	/**
	 * Extract method items from endpoints for the given key.
	 *
	 * @param  \Collective\Annotations\Routing\Annotations\EndpointCollection  $endpoints
	 * @param  string  $key
	 * @return array
	 */
	protected function extractFromEndpoints(EndpointCollection $endpoints, $key)
	{
		$items = [
			'index' => [], 'create' => [], 'store' => [], 'show' => [],
			'edit' => [], 'update' => [], 'destroy' => []
		];

		foreach ($this->getEndpointsWithResourceMethods($endpoints, $key) as $endpoint)
			$items[$endpoint->method] = array_merge($items[$endpoint->method], $endpoint->{$key});

		return $items;
	}

	/**
	 * Get all of the resource method endpoints with pathless filters.
	 *
	 * @param  \Collective\Annotations\Routing\Annotations\EndpointCollection  $endpoints
	 * @return array
	 */
	protected function getEndpointsWithResourceMethods(EndpointCollection $endpoints)
	{
		return Collection::make($endpoints)->filter(function($endpoint)
		{
			return ($endpoint instanceof MethodEndpoint &&
	                in_array($endpoint->method, $this->methods));

		})->all();
	}

}
