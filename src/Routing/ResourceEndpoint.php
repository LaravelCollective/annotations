<?php

namespace Collective\Annotations\Routing;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use ReflectionClass;

class ResourceEndpoint implements EndpointInterface
{
    use EndpointTrait;

    /**
     * All the resource controller methods.
     *
     * @var array
     */
    protected $methods = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];

    /**
     * The ReflectionClass instance for the controller class.
     *
     * @var ReflectionClass
     */
    public $reflection;

    /**
     * The route paths for the definition.
     *
     * This corresponds to a path for each applicable resource method.
     *
     * @var ResourcePath[]
     */
    public $paths;

    /**
     * The name of the resource.
     *
     * @var string
     */
    public $name;

    /**
     * The array of route names for the resource.
     *
     * @var array
     */
    public $names = [];

    /**
     * The only methods that should be included.
     *
     * @var array
     */
    public $only = [];

    /**
     * The methods that should not be included.
     *
     * @var array
     */
    public $except = [];

    /**
     * The class level "inherited" middleware that apply to the resource.
     *
     * @var array
     */
    public $classMiddleware = [];

    /**
     * The middleware that was applied at the method level.
     *
     * This array is keyed by resource method name (index, create, etc).
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

        $this->buildPaths();
    }

    /**
     * Build all the paths for the resource endpoint.
     *
     * @return void
     */
    protected function buildPaths()
    {
        foreach ($this->getIncludedMethods() as $method) {
            $this->paths[] = new ResourcePath($method);
        }
    }

    /**
     * Get the methods to be included in the resource.
     *
     * @return array
     */
    protected function getIncludedMethods()
    {
        if ($this->only) {
            return $this->only;
        } elseif ($this->except) {
            return array_diff($this->methods, $this->except);
        }

        return $this->methods;
    }

    /**
     * @inheritDoc
     */
    public function toRouteDefinition(): string
    {
        $routes = [];

        foreach ($this->paths as $path) {
            $routes[] = sprintf(
                $this->getTemplate(), 'Resource: '.$this->name.'@'.$path->method,
                $this->implodeArray($this->getMiddleware($path)),
                var_export($path->path, true), $this->implodeArray($path->where),
                var_export($path->domain, true), var_export($this->name, true),
                var_export($this->reflection->name, true), $this->implodeArray([$path->method]),
                $this->implodeArray($this->getNames($path))
            );
        }

        return implode(PHP_EOL.PHP_EOL, $routes);
    }

    /**
     * @inheritDoc
     */
    public function toRouteDefinitionDetail(): array
    {
        $routes = [];

        foreach ($this->paths as $path) {
            $routes[] = [
                'label' => $this->name . '@' . $path->method,
                'middleware' => $this->getMiddleware($path),
                'prefix' => $path->path,
                'where' => $path->where,
                'domain' => $path->domain,
                'name' => $this->name,
                'resource' => $this->reflection->name,
                'method' => $path->method,
                'names' => $this->getNames($path)
            ];
        }

        return $routes;
    }

    /**
     * Get all the middleware for the given path.
     *
     * This will also merge in any of the middleware applied at the route level.
     *
     * @param ResourcePath $path
     *
     * @return array
     */
    protected function getMiddleware(ResourcePath $path): array
    {
        $classMiddleware = $this->getClassMiddlewareForPath($path)->all();

        return array_merge($classMiddleware, Arr::get($this->middleware, $path->method, []));
    }

    /**
     * Get the class middleware for the given path.
     *
     * @param ResourcePath $path
     *
     * @return Collection
     */
    protected function getClassMiddlewareForPath(ResourcePath $path): Collection
    {
        return Collection::make($this->classMiddleware)->filter(function ($m) use ($path) {
            return $this->middlewareAppliesToMethod($path->method, $m);
        })
        ->map(function ($m) {
            return $m['name'];
        });
    }

    /**
     * Get the names for the given path.
     *
     * @param ResourcePath $path
     *
     * @return array
     */
    protected function getNames(ResourcePath $path): array
    {
        return isset($this->names[$path->method]) ? [$path->method => $this->names[$path->method]] : [];
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
        return '// %s
$router->group([\'middleware\' => [%s], \'prefix\' => %s, \'where\' => [%s], \'domain\' => %s], function() use ($router)
{
	$router->resource(%s, %s, [\'only\' => [%s], \'names\' => [%s]]);
});';
    }
}
