<?php

namespace Collective\Annotations;

use ReflectionException;
use ReflectionClass;

abstract class BaseScanner
{
    /**
     * The paths to scan for annotations.
     *
     * @var array
     */
    protected $scan = [];

    /**
     * Create a new scanner instance.
     *
     * @param array $scan
     *
     * @return void
     */
    public function __construct(array $scan)
    {
        $this->scan = $scan;
    }

    /**
     * Create a new scanner instance.
     *
     * @param array $scan
     *
     * @return static
     */
    public static function create(array $scan)
    {
        return new static($scan);
    }

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
