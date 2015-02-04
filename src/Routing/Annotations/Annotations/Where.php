<?php namespace Collective\Annotations\Routing\Annotations\Annotations;

use ReflectionClass;
use ReflectionMethod;
use Collective\Annotations\Routing\Annotations\MethodEndpoint;
use Collective\Annotations\Routing\Annotations\EndpointCollection;

/**
 * @Annotation
 */
class Where extends Annotation {

	/**
	 * {@inheritdoc}
	 */
	public function modify(MethodEndpoint $endpoint, ReflectionMethod $method)
	{
		foreach ($endpoint->getPaths() as $path)
		{
			$path->where = array_merge($path->where, (array) $this->value);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function modifyCollection(EndpointCollection $endpoints, ReflectionClass $class)
	{
		foreach ($endpoints->getAllPaths() as $path)
		{
			$path->where = array_merge($path->where, $this->value);
		}
	}

}
