<?php

namespace Collective\Annotations\Events\Annotations;

use Collective\Annotations\Scanner as BaseScanner;

class Scanner extends BaseScanner
{
    /**
     * @var ScanStrategyInterface
     */
    protected $strategy;

    public function __construct(ScanStrategyInterface $strategy, array $scan = [])
    {
        $this->strategy = $strategy;
        $this->scan = $scan;
    }

    /**
     * Convert the scanned annotations/attributes into event definitions.
     */
    public function getEventDefinitions(): string
    {
        $output = '';

        foreach ($this->getClassesToScan() as $class) {
            foreach ($class->getMethods() as $method) {
                foreach ($this->strategy->getEvents($method) as $events) {
                    $output .= $this->buildListener($class->name, $method->name, $events);
                }
            }
        }

        return trim($output);
    }

    /**
     * @return ScanStrategyInterface
     */
    public function getStrategy(): ScanStrategyInterface
    {
        return $this->strategy;
    }

    /**
     * Build the event listener for the class and method.
     *
     * @param string $class
     * @param string $method
     * @param array  $events
     *
     * @return string
     */
    protected function buildListener($class, $method, $events): string
    {
        return sprintf('$events->listen(%s, \''.$class.'@'.$method.'\');', var_export($events, true)).PHP_EOL;
    }
}
