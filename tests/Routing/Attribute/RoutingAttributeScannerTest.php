<?php

use Collective\Annotations\Routing\Attributes\Scanner;
use PHPUnit\Framework\TestCase;

class RoutingAttributeScannerTest extends TestCase
{
    public function testProperRouteDefinitionsAreGenerated()
    {
        require_once __DIR__ . '/fixtures/attributes/BasicController.php';
        $scanner = $this->makeScanner(['App\Http\Controllers\Attributes\BasicController']);

        $definition = str_replace(PHP_EOL, "\n", $scanner->getRouteDefinitions());
        $this->assertEquals(trim(file_get_contents(__DIR__ . '/results/annotation-basic.php')), $definition);
    }

    public function testProperRouteDefinitionsDetailAreGenerated()
    {
        require_once __DIR__ . '/fixtures/attributes/BasicController.php';
        $scanner = $this->makeScanner(['App\Http\Controllers\Attributes\BasicController']);

        $routeDetail = $scanner->getRouteDefinitionsDetail();
        $this->assertEquals(include __DIR__ . '/results/route-detail-basic.php', $routeDetail);
    }

    public function testAnyAnnotation()
    {
        require_once __DIR__ . '/fixtures/attributes/AnyController.php';
        $scanner = $this->makeScanner(['App\Http\Controllers\Attributes\AnyController']);

        $definition = str_replace(PHP_EOL, "\n", $scanner->getRouteDefinitions());
        $this->assertEquals(trim(file_get_contents(__DIR__ . '/results/annotation-any.php')), $definition);
    }

    public function testAnyAnnotationDetail()
    {
        require_once __DIR__ . '/fixtures/attributes/AnyController.php';
        $scanner = $this->makeScanner(['App\Http\Controllers\Attributes\AnyController']);

        $routeDetail = $scanner->getRouteDefinitionsDetail();
        $this->assertEquals(include __DIR__ . '/results/route-detail-any.php', $routeDetail);
    }

    public function testWhereAnnotation()
    {
        require_once __DIR__ . '/fixtures/attributes/WhereController.php';
        $scanner = $this->makeScanner(['App\Http\Controllers\Attributes\WhereController']);

        $definition = $scanner->getRouteDefinitions();
        $this->assertEquals(trim(file_get_contents(__DIR__ . '/results/annotation-where.php')), $definition);
    }

    public function testWhereAnnotationDetail()
    {
        require_once __DIR__ . '/fixtures/attributes/WhereController.php';
        $scanner = $this->makeScanner(['App\Http\Controllers\Attributes\WhereController']);

        $routeDetail = $scanner->getRouteDefinitionsDetail();
        $this->assertEquals(include __DIR__ . '/results/route-detail-where.php', $routeDetail);
    }

    public function testPrefixAnnotation()
    {
        require_once __DIR__ . '/fixtures/attributes/PrefixController.php';
        $scanner = $this->makeScanner(['App\Http\Controllers\Attributes\PrefixController']);

        $definition = str_replace(PHP_EOL, "\n", $scanner->getRouteDefinitions());
        $this->assertEquals(trim(file_get_contents(__DIR__ . '/results/annotation-prefix.php')), $definition);
    }

    public function testInheritedControllerAnnotations()
    {
        require_once __DIR__ . '/fixtures/attributes/AnyController.php';
        require_once __DIR__ . '/fixtures/attributes/ChildController.php';
        $scanner = $this->makeScanner([
            'App\Http\Controllers\Attributes\AnyController',
            'App\Http\Controllers\Attributes\ChildController'
        ]);

        $definition = str_replace(PHP_EOL, "\n", $scanner->getRouteDefinitions());
        $this->assertEquals(trim(file_get_contents(__DIR__ . '/results/annotation-child.php')), $definition);
    }

    /**
     * Construct a route annotation scanner.
     *
     * @param array $paths
     *
     * @return
     */
    protected function makeScanner($paths)
    {
        return Scanner::create($paths);
    }
}
