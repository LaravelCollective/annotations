<?php

namespace Collective\Annotations\Routing;

trait EndpointTrait
{
    /**
     * Determine if the middleware applies to a given method.
     *
     * @param string $method
     * @param array  $middleware
     *
     * @return bool
     */
    protected function middlewareAppliesToMethod($method, array $middleware): bool
    {
        if (!empty($middleware['only']) && !in_array($method, $middleware['only'])) {
            return false;
        } elseif (!empty($middleware['except']) && in_array($method, $middleware['except'])) {
            return false;
        }

        return true;
    }

    /**
     * Get the controller method for the given endpoint path.
     *
     * @param AbstractPath $path
     *
     * @return string
     */
    public function getMethodForPath(AbstractPath $path): string
    {
        return $path->method;
    }

    /**
     * Add the given path definition to the endpoint.
     *
     * @param AbstractPath $path
     *
     * @return void
     */
    public function addPath(AbstractPath $path)
    {
        $this->paths[] = $path;
    }

    /**
     * Implode the given list into a comma separated string.
     *
     * @param array $array
     *
     * @return string
     */
    protected function implodeArray(array $array): string
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_string($key)) {
                $results[] = "'".$key."' => '".$value."'";
            } else {
                $results[] = "'".$value."'";
            }
        }

        return count($results) > 0 ? implode(', ', $results) : '';
    }
}
