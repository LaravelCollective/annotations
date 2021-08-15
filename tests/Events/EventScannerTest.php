<?php

use Collective\Annotations\Events\Annotations\AnnotationStrategy;
use Collective\Annotations\Events\Attributes\AttributeStrategy;
use Collective\Annotations\Events\Scanner;
use Collective\Annotations\Events\ScanStrategyInterface;
use PHPUnit\Framework\TestCase;

class EventScannerTest extends TestCase
{
    /**
     * @dataProvider strategyProvider
     *
     * @param ScanStrategyInterface $strategy
     */
    public function testProperEventDefinitionsAreGenerated(ScanStrategyInterface $strategy)
    {
        require_once __DIR__ . '/fixtures/handlers/BasicEventHandler.php';
        $scanner = new Scanner($strategy, ['App\Handlers\Events\BasicEventHandler']);

        $definition = str_replace(PHP_EOL, "\n", $scanner->getEventDefinitions());
        $this->assertEquals(trim(file_get_contents(__DIR__ . '/results/annotation-basic.php')), $definition);
    }

    /**
     * @dataProvider strategyProvider
     *
     * @param ScanStrategyInterface $strategy
     */
    public function testProperMultipleEventDefinitionsAreGenerated(ScanStrategyInterface $strategy)
    {
        require_once __DIR__ . '/fixtures/handlers/MultipleEventHandler.php';
        $scanner = new Scanner($strategy, ['App\Handlers\Events\MultipleEventHandler']);

        $definition = str_replace(PHP_EOL, "\n", $scanner->getEventDefinitions());
        $this->assertEquals(trim(file_get_contents(__DIR__ . '/results/annotation-multiple.php')), $definition);
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
