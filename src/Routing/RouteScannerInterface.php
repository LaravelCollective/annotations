<?php

namespace Collective\Annotations\Routing;

interface RouteScannerInterface
{
    /**
     * Convert the scanned annotations/attributes into route definitions.
     */
    public function getRouteDefinitions(): string;

    /**
     * Give information about the scanned annotations/attributes related to route definition.
     */
    public function getRouteDefinitionsDetail(): array;
}