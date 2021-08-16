<?php

namespace Collective\Annotations;

use ReflectionException;
use ReflectionClass;

trait ScannerTrait
{
    /**
     * The paths to scan for annotations.
     *
     * @var array
     */
    protected $scan = [];

    /**
     * Get all of the ReflectionClass instances in the scan array.
     *
     * @return ReflectionClass[]
     */
    protected function getClassesToScan(): array
    {
        $classes = [];

        foreach ($this->scan as $class) {
            try {
                $classes[] = new ReflectionClass($class);
            } catch (ReflectionException $e) {
                //
            }
        }

        return $classes;
    }

    /**
     * Set the classes to scan.
     *
     * @param array $scans
     */
    public function setClassesToScan(array $scans)
    {
        $this->scan = $scans;
    }
}
