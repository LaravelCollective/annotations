<?php

use Collective\Annotations\Database\Eloquent\Annotations\Scanner;
use Collective\Annotations\Database\Eloquent\Annotations\InvalidBindingResolverException;
use PHPUnit\Framework\TestCase;

class ModelAnnotationScannerTest extends TestCase
{
    public function testProperModelDefinitionsAreGenerated()
    {
        require_once __DIR__.'/fixtures/annotations/AnyModel.php';
        $scanner = $this->makeScanner(['App\User']);

        $definition = str_replace(PHP_EOL, "\n", $scanner->getModelDefinitions());
        $this->assertEquals(trim(file_get_contents(__DIR__.'/results/annotation.php')), $definition);
    }

    public function testNonEloquentModelThrowsException()
    {
        $this->expectException(InvalidBindingResolverException::class);
        $this->expectExceptionMessage('Class [App\NonEloquentModel] is not a subclass of [Illuminate\Database\Eloquent\Model]');

        require_once __DIR__.'/fixtures/annotations/NonEloquentModel.php';
        $scanner = $this->makeScanner(['App\NonEloquentModel']);

        $definition = str_replace(PHP_EOL, "\n", $scanner->getModelDefinitions());
        $this->assertEquals(trim(file_get_contents(__DIR__.'/results/annotation.php')), $definition);
    }

    /**
     * Construct a model annotation scanner.
     *
     * @param array $paths
     *
     * @return
     */
    protected function makeScanner($paths)
    {
        $scanner = Scanner::create($paths);

        $scanner->addAnnotationNamespace(
            'Collective\Annotations\Database\Eloquent\Annotations\Annotations',
            realpath(__DIR__.'/../../src/Database/Eloquent/Annotations/Annotations')
        );

        return $scanner;
    }
}
