<?php

use Collective\Annotations\Database\Eloquent\Annotations\AnnotationStrategy;
use Collective\Annotations\Database\Eloquent\Attributes\AttributeStrategy;
use Collective\Annotations\Database\InvalidBindingResolverException;
use Collective\Annotations\Database\Scanner;
use Collective\Annotations\Database\ScanStrategyInterface;
use PHPUnit\Framework\TestCase;

class ModelStrategyTest extends TestCase
{

    /**
     * @dataProvider strategyProvider
     *
     * @param ScanStrategyInterface $strategy
     */
    public function testSupportedModel(ScanStrategyInterface $strategy)
    {
        require_once __DIR__.'/fixtures/annotations/AnyModel.php';
        $class = new ReflectionClass('App\User');

        self::assertEquals(true, $strategy->support($class));
        self::assertEquals(['users'], $strategy->getBindings($class));
    }


    /**
     * @dataProvider strategyProvider
     *
     * @param ScanStrategyInterface $strategy
     */
    public function testNonSupportedModel(ScanStrategyInterface $strategy)
    {
        require_once __DIR__.'/fixtures/annotations/NonSupportedModel.php';
        $class = new ReflectionClass('App\NonSupportedModel');

        self::assertEquals(false, $strategy->support($class));
        self::assertEquals([], $strategy->getBindings($class));
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
