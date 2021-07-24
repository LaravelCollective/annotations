<?php

use Collective\Annotations\Database\Eloquent\Annotations\InvalidBindingResolverException;
use Collective\Annotations\Database\Eloquent\Attributes\Scanner;
use PHPUnit\Framework\TestCase;

class ModelAttributeScannerTest extends TestCase
{
    public function testProperModelDefinitionsAreGenerated()
    {
        require_once __DIR__.'/fixtures/annotations/AnyModel.php';
        $scanner = $this->makeScanner(['App\Attributes\User']);

        $definition = str_replace(PHP_EOL, "\n", $scanner->getModelDefinitions());
        $this->assertEquals(trim(file_get_contents(__DIR__.'/results/annotation.php')), $definition);
    }

    public function testNonEloquentModelThrowsException()
    {
        $this->expectException(InvalidBindingResolverException::class);
        $this->expectExceptionMessage('Class [App\Attributes\NonEloquentModel] is not a subclass of [Illuminate\Database\Eloquent\Model]');

        require_once __DIR__.'/fixtures/annotations/NonEloquentModel.php';
        $scanner = $this->makeScanner(['App\Attributes\NonEloquentModel']);

        $definition = str_replace(PHP_EOL, "\n", $scanner->getModelDefinitions());
        $this->assertEquals(trim(file_get_contents(__DIR__.'/results/annotation.php')), $definition);
    }

    /**
     * Construct a model attribute scanner.
     *
     * @param array $paths
     *
     * @return
     */
    protected function makeScanner($paths)
    {
        return Scanner::create($paths);
    }
}
