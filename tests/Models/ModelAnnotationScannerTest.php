<?php

use Collective\Annotations\Database\Eloquent\Annotations\Scanner;

class ModelAnnotationScannerTest extends PHPUnit_Framework_TestCase {

	public function testProperRouteDefinitionsAreGenerated()
	{
		require_once __DIR__.'/fixtures/annotations/AnyModel.php';
		$scanner = $this->makeScanner( ['App\User'] );

		$definition = str_replace(PHP_EOL, "\n", $scanner->getModelDefinitions());
		$this->assertEquals(trim(file_get_contents(__DIR__.'/results/annotation.php')), $definition);
	}

	/**
	 * Construct a route annotation scanner
	 *
	 * @param  array $paths
	 * @return
	 */
	protected function makeScanner( $paths )
	{
		$scanner = Scanner::create( $paths );

		$scanner->addAnnotationNamespace(
			'Collective\Annotations\Database\Eloquent\Annotations\Annotations',
			realpath(__DIR__.'/../../src/Database/Eloquent//Annotations/Annotations')
		);

		return $scanner;
	}
}
