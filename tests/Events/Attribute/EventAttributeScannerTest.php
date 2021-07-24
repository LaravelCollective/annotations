<?php

use Collective\Annotations\Events\Attributes\Scanner;
use PHPUnit\Framework\TestCase;

class EventAttributeScannerTest extends TestCase
{
    public function testProperEventDefinitionsAreGenerated()
    {
        require_once __DIR__.'/fixtures/attributes/BasicEventHandler.php';
        $scanner = $this->makeScanner(['App\Handlers\Events\Attributes\BasicEventHandler']);

        $definition = str_replace(PHP_EOL, "\n", $scanner->getEventDefinitions());
        $this->assertEquals(trim(file_get_contents(__DIR__.'/results/annotation-basic.php')), $definition);
    }

    public function testProperMultipleEventDefinitionsAreGenerated()
    {
        require_once __DIR__.'/fixtures/attributes/MultipleEventHandler.php';
        $scanner = $this->makeScanner(['App\Handlers\Events\Attributes\MultipleEventHandler']);

        $definition = str_replace(PHP_EOL, "\n", $scanner->getEventDefinitions());
        $this->assertEquals(trim(file_get_contents(__DIR__.'/results/annotation-multiple.php')), $definition);
    }


    /**
     * Construct an event attribute scanner.
     *
     * @param array $paths
     *
     * @return Scanner
     */
    protected function makeScanner(array $paths): Scanner
    {
        return Scanner::create($paths);
    }
}
