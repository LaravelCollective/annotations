<?php

namespace Collective\Annotations\Routing;

use Illuminate\Support\Collection;
use ReflectionClass;

class MethodEndpoint implements EndpointInterface
{
    use EndpointTrait;

    /**
     * The ReflectionClass instance for the controller class.
     *
     * @var ReflectionClass
     */
    public $reflection;

    /**
     * The method that handles the route.
     *
     * @var string
     */
    public $method;

    /**
     * The route paths for the definition.
     *
     * @var array[Path]
     */
    public $paths = [];

    /**
     * The controller and method that handles the route.
     *
     * @var string
     */
    public $uses;

    /**
     * All the class level "inherited" middleware defined for the pathless endpoint.
     *
     * @var array
     */
    public $classMiddleware = [];

    /**
     * All the middleware defined for the pathless endpoint.
     *
     * @var array
     */
    public $middleware = [];

    /**
     * Create a new route definition instance.
     *
     * @param array $attributes
     *
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * @inheritDoc
     */
    public function toRouteDefinition(): string
    {
        $routes = [];

        foreach ($this->paths as $path) {
            $routes[] = sprintf(
                $this->getTemplate(), $path->verb, $path->path, $this->uses, var_export($path->as, true),
                $this->getMiddleware($path), $this->implodeArray($path->where), var_export($path->domain, true)
            );
        }

        return implode(PHP_EOL . PHP_EOL, $routes);
    }

    /**
     * @inheritDoc
     */
    public function toRouteDefinitionDetail(): array
    {
        $routes = [];

        foreach ($this->paths as $path) {
            $routes[] = [
                'verb' => $path->verb,
                'path' => $path->path,
                'uses' => $this->uses,
                'as' => $path->as,
                'middleware' => array_merge($this->getClassMiddlewareForPath($path)->all(), $path->middleware, $this->middleware),
                'where' => $path->where,
                'domain' => $path->domain,
            ];
        }

        return $routes;
    }

    /**
     * Get the middleware for the path.
     *
     * @param AbstractPath $path
     *
     * @return string
     */
    protected function getMiddleware(AbstractPath $path): string
    {
        $classMiddleware = $this->getClassMiddlewareForPath($path)->all();

        return $this->implodeArray(
            array_merge($classMiddleware, $path->middleware, $this->middleware)
        );
    }

    /**
     * Get the class middleware for the given path.
     *
     * @param AbstractPath $path
     *
     * @return Collection
     */
    protected function getClassMiddlewareForPath(AbstractPath $path): Collection
    {
        return Collection::make($this->classMiddleware)->filter(function ($m) {
            return $this->middlewareAppliesToMethod($this->method, $m);
        })
            ->map(function ($m) {
                return $m['name'];
            });
    }

    /**
     * @inheritDoc
     */
    public function hasPaths(): bool
    {
        return count($this->paths) > 0;
    }

    /**
     * @inheritDoc
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    /**
     * Get the template for the endpoint.
     *
     * @return string
     */
    protected function getTemplate(): string
    {
        return '$router->%s(\'%s\', [
	\'uses\' => \'%s\',
	\'as\' => %s,
	\'middleware\' => [%s],
	\'where\' => [%s],
	\'domain\' => %s,
]);';
    }
}
