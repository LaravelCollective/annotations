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

    protected function setUp(): void
    {
        $this->app = m::mock('Illuminate\Contracts\Foundation\Application');
    }

    /**
     * Test that the scanner finds the events annotations directory as expected
     * Ensure that the Hears class is loaded into the scanner
     *
     * @covers \Collective\Annotations\AnnotationScanner::addAnnotationNamespace
     * @covers \Collective\Annotations\AnnotationScanner::registerAnnotationsPathWithRegistry
     * @covers \Collective\Annotations\NamespaceToPathConverterTrait::getPathFromNamespace
     */
    public function testAddAnnotationNamespaceWithoutSpecifiedPath()
    {
        //update the app namespace to be App
        $this->app->shouldReceive('getNamespace')->once()
            ->andReturn('App\\');
        Container::setInstance($this->app);

        //update the app path in the facade to use `src` which is where the event scanner lives in this project
        App::shouldReceive('make')
            ->with('path')->once()
            ->andReturn('src');

        $scanner = new Scanner(['App\Handlers\Events\BasicEventHandler']);

        //underneath the scanner converts the App namespace to the src dir, based on what we set into the app above
        $return = $scanner->addAnnotationNamespace(
            'App\Events\Annotations\Annotations'
        );

        $this->assertInstanceOf(Scanner::class, $return);
        $this->assertTrue(
            AnnotationRegistry::loadAnnotationClass('Collective\Annotations\Events\Annotations\Annotations\Hears')
        );
    }

    /**
     * Ensures that the class that doesn't exist is ignored by the scanner
     * And that the existing class is loaded in as expected
     */
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
