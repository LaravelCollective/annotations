<?php

use Collective\Annotations\Events\Annotations\AnnotationStrategy;
use Collective\Annotations\Events\Attributes\AttributeStrategy;
use Collective\Annotations\Events\ScanStrategyInterface;
use PHPUnit\Framework\TestCase;

class EventStrategyTest extends TestCase
{
    protected function setUp(): void
    {
        if (PHP_MAJOR_VERSION < 8) {
            $this->markTestSkipped('Skip because php version is less than 8.0.0');
        }
    }

    /**
     * @dataProvider handlerProvider
     * @param string $fixturePath
     * @param string $fixtureClass
     */
    public function testStrategyResultAreEqual(string $fixturePath, string $fixtureClass)
    {
        require_once __DIR__ . $fixturePath;
        $class = new ReflectionClass($fixtureClass);

        $annotationStrategy = self::annotationStrategy();
        $attributeStrategy = self::attributeStrategy();

        foreach ($class->getMethods() as $reflectionMethod) {
            self::assertTrue($annotationStrategy->getEvents($reflectionMethod) === $attributeStrategy->getEvents($reflectionMethod));
        }
    }

    public function handlerProvider(): array
    {
        return [
            'BasicEventHandler' => [
                '/fixtures/handlers/BasicEventHandler.php',
                'App\Handlers\Events\BasicEventHandler',
            ],
            'MultipleEventHandler' => [
                '/fixtures/handlers/MultipleEventHandler.php',
                'App\Handlers\Events\MultipleEventHandler',
            ],
        ];
    }

    public function strategyProvider(): array
    {
        return [
            'annotationStrategy' => [self::annotationStrategy()],
            'attributeStrategy' => [self::attributeStrategy()],
        ];
    }

    protected static function attributeStrategy(): ScanStrategyInterface
    {
        return new AttributeStrategy();
    }

    protected static function annotationStrategy(): ScanStrategyInterface
    {
        $strategy = new AnnotationStrategy();
        $strategy->addAnnotationNamespace(
            'Collective\Annotations\Events\Annotations\Annotations',
            realpath(__DIR__.'/../../src/Events/Annotations/Annotations')
        );
        return $strategy;
    }
}
