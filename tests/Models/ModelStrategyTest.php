<?php

use Collective\Annotations\Database\Eloquent\Annotations\AnnotationStrategy;
use Collective\Annotations\Database\Eloquent\Attributes\AttributeStrategy;
use Collective\Annotations\Database\ScanStrategyInterface;
use PHPUnit\Framework\TestCase;

class ModelStrategyTest extends TestCase
{
    protected function setUp(): void
    {
        if (PHP_MAJOR_VERSION < 8) {
            $this->markTestSkipped('Skip because php version is less than 8.0.0');
        }
    }

    /**
     * @dataProvider modelProvider
     * @param string $fixturePath
     * @param string $fixtureClass
     */
    public function testStrategyResultAreEqual(string $fixturePath, string $fixtureClass)
    {
        require_once __DIR__ . $fixturePath;
        $class = new ReflectionClass($fixtureClass);

        $annotationStrategy = self::annotationStrategy();
        $attributeStrategy = self::attributeStrategy();

        self::assertTrue($annotationStrategy->support($class) === $attributeStrategy->support($class));
        self::assertTrue($annotationStrategy->getBindings($class) === $attributeStrategy->getBindings($class));
    }

    public function modelProvider(): array
    {
        return [
            'AnyModel' => [
                '/fixtures/annotations/AnyModel.php',
                'App\User',
            ],
            'NonEloquentModel' => [
                '/fixtures/annotations/NonEloquentModel.php',
                'App\NonEloquentModel',
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
            'Collective\Annotations\Database\Eloquent\Annotations\Annotations',
            realpath(__DIR__.'/../../src/Database/Eloquent/Annotations/Annotations')
        );
        return $strategy;
    }
}
