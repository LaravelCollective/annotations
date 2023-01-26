<?php

namespace Collective\Annotations\Database;

use Collective\Annotations\Scanner as BaseScanner;
use ReflectionClass;

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
     * @throws InvalidBindingResolverException
     */
    public function getModelDefinitions(): string
    {
        $output = '';

        foreach ($this->getClassesToScan() as $class) {
            if (!$this->strategy->support($class)) {
                continue;
            }

            if (!$this->extendsEloquent($class)) {
                throw new InvalidBindingResolverException('Class ['.$class->name.'] is not a subclass of [Illuminate\Database\Eloquent\Model].');
            }

            foreach ($this->strategy->getBindings($class) as $binding) {
                $output .= $this->buildBinding($binding, $class->name);
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
     * Determine if a class extends Eloquent.
     *
     * @param ReflectionClass $class
     *
     * @return bool
     */
    protected function extendsEloquent(ReflectionClass $class): bool
    {
        return $class->isSubclassOf('Illuminate\Database\Eloquent\Model');
    }

    /**
     * Build the event listener for the class and method.
     *
     * @param BindInterface $binding
     * @param string $class
     *
     * @return string
     */
    protected function buildBinding($binding, $class): string
    {
        $code = sprintf('$router->model(\'%s\', \'%s\');', $binding->getKey(), $class).PHP_EOL;
        if ($binding->getPattern()) {
            $code .= sprintf("\$router->pattern('%s', '%s');", $binding->getKey(), $binding->getPattern()).PHP_EOL;
        }
        return $code;
    }
}
