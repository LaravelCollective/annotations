<?php

use Collective\Annotations\AnnotationScanner;
use Collective\Annotations\Events\Annotations\Scanner;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\App;
use PHPUnit\Framework\TestCase;
use Mockery as m;

class AnnotationScannerTest extends TestCase
{
    use m\Adapter\Phpunit\MockeryPHPUnitIntegration;

    /**
     * @var m\MockInterface|Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    public function setUp(): void
    {
        $this->app = m::mock('Illuminate\Contracts\Foundation\Application');
    }

    /**
     * @covers \Collective\Annotations\AnnotationScanner::addAnnotationNamespace
     * @covers \Collective\Annotations\AnnotationScanner::registerAnnotationsPathWithRegistry
     * @covers \Collective\Annotations\NamespaceToPathConverterTrait::getPathFromNamespace
     */
    public function testAddAnnotationNamespaceWithoutSpecifiedPath()
    {
        $this->app->shouldReceive('getNamespace')->once()
            ->andReturn('App\\');
        Container::setInstance($this->app);

        App::shouldReceive('make')
            ->with('path')->once()
            ->andReturn('src');

        $scanner = new Scanner(['App\Handlers\Events\BasicEventHandler']);

        $return = $scanner->addAnnotationNamespace(
            'App\Events\Annotations\Annotations'
        );

        $this->assertInstanceOf(Scanner::class, $return);
        $this->assertTrue(
            AnnotationRegistry::loadAnnotationClass('Collective\Annotations\Events\Annotations\Annotations\Hears')
        );
    }

    public function testSetClassesToScan()
    {
        require_once __DIR__.'/Events/fixtures/annotations/BasicEventHandler.php';

        $scanner = new TestAnnotationScanner([]);
        $scanner->setClassesToScan([
            'App\Handlers\Events\BasicEventHandler',
            'App\Handlers\Events\NonExistentHandler',
        ]);

        $classesToScan = $scanner->getClassesToScan();
        $this->assertCount(1, $classesToScan);
        $this->assertEquals('App\Handlers\Events\BasicEventHandler', reset($classesToScan)->name);
    }
}

/**
 * Class TestAnnotationScanner
 *
 * Override class to expose the protected getClassesToScan method
 */
class TestAnnotationScanner extends AnnotationScanner
{
    public function getClassesToScan()
    {
        return parent::getClassesToScan();
    }
}
