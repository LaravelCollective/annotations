<?php

use Collective\Annotations\AnnotationStrategyTrait;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\App;
use PHPUnit\Framework\TestCase;
use Mockery as m;

class AnnotationStrategyTraitTest extends TestCase
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
     * @covers \Collective\Annotations\AnnotationStrategyTrait::addAnnotationNamespace
     * @covers \Collective\Annotations\AnnotationStrategyTrait::registerAnnotationsPathWithRegistry
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

        $strategy = new class() { use AnnotationStrategyTrait; };

        //underneath the scanner converts the App namespace to the src dir, based on what we set into the app above
        $strategy->addAnnotationNamespace(
            'App\Events\Annotations\Annotations'
        );

        $this->assertTrue(
            AnnotationRegistry::loadAnnotationClass('Collective\Annotations\Events\Annotations\Annotations\Hears')
        );
    }
}
