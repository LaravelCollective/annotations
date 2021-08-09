<?php

namespace Collective\Annotations\Database;

interface ModelScannerInterface
{
    /**
     * Convert the scanned annotations/attributes into route definitions.
     */
    public function getModelDefinitions(): string;
}
