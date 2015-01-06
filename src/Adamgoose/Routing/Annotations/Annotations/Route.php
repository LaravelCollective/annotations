<?php namespace Adamgoose\Routing\Annotations\Annotations;

use ReflectionMethod;
use Adamgoose\Routing\Annotations\Path;
use Adamgoose\Routing\Annotations\MethodEndpoint;

abstract class Route extends Annotation {

	/**
	 * {@inheritdoc}
	 */
	public function modify(MethodEndpoint $endpoint, ReflectionMethod $method)
	{
		$endpoint->addPath(new Path(
			strtolower(class_basename(get_class($this))), $this->domain, $this->value,
			$this->as, (array) $this->middleware, (array) $this->where
		));
	}

}
