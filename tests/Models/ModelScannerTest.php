<?php

use Collective\Annotations\Database\Eloquent\Annotations\AnnotationStrategy;
use Collective\Annotations\Database\Eloquent\Attributes\AttributeStrategy;
use Collective\Annotations\Database\InvalidBindingResolverException;
use Collective\Annotations\Database\Scanner;
use Collective\Annotations\Database\ScanStrategyInterface;
use PHPUnit\Framework\TestCase;

class ModelScannerTest extends TestCase
{

    /**
     * @dataProvider strategyProvider
     *
     * @param ScanStrategyInterface $strategy
     * @throws InvalidBindingResolverException
     */
    public function testProperModelDefinitionsAreGenerated(ScanStrategyInterface $strategy)
    {
        require_once __DIR__.'/fixtures/annotations/AnyModel.php';
        $scanner = new Scanner($strategy, ['App\User']);

        $definition = str_replace(PHP_EOL, "\n", $scanner->getModelDefinitions());
        $this->assertEquals(trim(file_get_contents(__DIR__.'/results/annotation.php')), $definition);
    }

    /**
     * @dataProvider strategyProvider
     *
     * @param ScanStrategyInterface $strategy
     * @throws InvalidBindingResolverException
     */
    public function testNonEloquentModelThrowsException(ScanStrategyInterface $strategy)
    {
        $this->expectException(InvalidBindingResolverException::class);
        $this->expectExceptionMessage('Class [App\NonEloquentModel] is not a subclass of [Illuminate\Database\Eloquent\Model]');

        require_once __DIR__.'/fixtures/annotations/NonEloquentModel.php';
        $scanner = new Scanner($strategy, ['App\NonEloquentModel']);
        $scanner->getModelDefinitions();
    }


    public function strategyProvider(): array
    {
        $strategies = ['annotationStrategy' => [self::annotationStrategy()]];
        if (PHP_MAJOR_VERSION >= 8) {
            $strategies['attributeStrategy'] = [self::attributeStrategy()];
        }
        return $strategies;
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
