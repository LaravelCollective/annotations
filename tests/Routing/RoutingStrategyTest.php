<?php

use Collective\Annotations\Routing\Annotations\AnnotationStrategy;
use Collective\Annotations\Routing\Meta;
use Collective\Annotations\Routing\Attributes\AttributeStrategy;
use Collective\Annotations\Routing\ScanStrategyInterface;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class RoutingStrategyTest extends TestCase
{
    protected function setUp(): void
    {
        if (PHP_MAJOR_VERSION < 8) {
            $this->markTestSkipped('Skip because php version is less than 8.0.0');
        }
    }

    /**
     * @dataProvider controllerProvider
     * @param string $fixturePath
     * @param string $fixtureClass
     */
    public function testStrategyResultAreEqual(string $fixturePath, string $fixtureClass)
    {
        require_once __DIR__ . $fixturePath;
        $class = new ReflectionClass($fixtureClass);

        $annotationStrategy = self::annotationStrategy();
        $attributeStrategy = self::attributeStrategy();

        collect($annotationStrategy->getClassMetaList($class))
            ->zip($attributeStrategy->getClassMetaList($class))
            ->each(function (Collection $metas) use ($class) {

                /** @var Meta $annotation */
                $annotation = $metas[0];

                /** @var Meta $attribute */
                $attribute = $metas[1];

                // Check that Attribute is a subclass of Annotation
                // because Attribute delegates processing to Annotation.
                self::assertTrue($attribute instanceof $annotation);
            });

        $attributeMethodMetaLists = $attributeStrategy->getMethodMetaLists($class);

        foreach ($annotationStrategy->getMethodMetaLists($class) as $method => $annotationMethodMetaList) {
            $attributeMethodMetaList = $attributeMethodMetaLists[$method];

            // Check that the number of Meta given to the endpoint is equal.
            self::assertTrue(count($annotationMethodMetaList) === count($attributeMethodMetaList));
        }
    }


    public function controllerProvider(): array
    {
        return [
            'AnyController' => [
                '/fixtures/annotations/AnyController.php',
                'App\Http\Controllers\AnyController',
            ],
            'BasicController' => [
                '/fixtures/annotations/BasicController.php',
                'App\Http\Controllers\BasicController',
            ],
            'ChildController' => [
                '/fixtures/annotations/ChildController.php',
                'App\Http\Controllers\ChildController',
            ],
            'PrefixController' => [
                '/fixtures/annotations/PrefixController.php',
                'App\Http\Controllers\PrefixController',
            ],
            'WhereController' => [
                '/fixtures/annotations/WhereController.php',
                'App\Http\Controllers\WhereController',
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
            'Collective\Annotations\Routing\Annotations\Annotations',
            realpath(__DIR__ . '/../../src/Routing/Annotations/Annotations')
        );
        return $strategy;
    }
}
