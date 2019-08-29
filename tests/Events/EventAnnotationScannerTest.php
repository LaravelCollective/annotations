<?php

use Collective\Annotations\Events\Annotations\Scanner;
use PHPUnit\Framework\TestCase;

class EventAnnotationScannerTest extends TestCase
{
	public function testProperEventDefinitionsAreGenerated()
	{
		require_once __DIR__.'/fixtures/annotations/BasicEventHandler.php';
		$scanner = $this->makeScanner(['App\Handlers\Events\BasicEventHandler']);

		$definition = str_replace(PHP_EOL, "\n", $scanner->getEventDefinitions());
		$this->assertEquals(trim(file_get_contents(__DIR__.'/results/annotation-basic.php')), $definition);
	}

    public function testProperMultipleEventDefinitionsAreGenerated()
    {
        require_once __DIR__.'/fixtures/annotations/MultipleEventHandler.php';
        $scanner = $this->makeScanner(['App\Handlers\Events\MultipleEventHandler']);

        $definition = str_replace(PHP_EOL, "\n", $scanner->getEventDefinitions());
        $this->assertEquals(trim(file_get_contents(__DIR__.'/results/annotation-multiple.php')), $definition);
    }


    /**
     * Construct an event annotation scanner.
     *
     * @param array $paths
     *
     * @return Scanner
     */
    protected function makeScanner(array $paths): Scanner
    {
        $scanner = Scanner::create($paths);

        $scanner->addAnnotationNamespace(
            'Collective\Annotations\Events\Annotations\Annotations',
            realpath(__DIR__.'/../../src/Events/Annotations/Annotations')
        );

        return $scanner;
    }
}
