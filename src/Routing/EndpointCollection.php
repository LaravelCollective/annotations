<?php

namespace Collective\Annotations\Routing;

use Illuminate\Support\Collection;

class EndpointCollection extends Collection
{
    /**
     * Get all of the paths for the given endpoint collection.
     *
     * @return array
     */
    public function getAllPaths()
    {
        $paths = [];

        foreach ($this as $endpoint) {
            foreach ($endpoint->getPaths() as $path) {
                $paths[] = $path;
            }
        }

        return $paths;
    }
}
