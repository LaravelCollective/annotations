<?php

use Collective\Annotations\Scanner;
use PHPUnit\Framework\TestCase;

class AnnotationScannerTest extends TestCase
{
    /**
     * Ensures that the class that doesn't exist is ignored by the scanner
     * And that the existing class is loaded in as expected
     */
    public function testSetClassesToScan()
    {
        require_once __DIR__.'/Events/fixtures/handlers/BasicEventHandler.php';

        // Override class to expose the protected getClassesToScan method
        $scanner = new class() extends Scanner {
            public function getClassesToScan(): array
            {
                return parent::getClassesToScan();
            }
        };

        $scanner->setClassesToScan([
            'App\Handlers\Events\BasicEventHandler',
            'App\Handlers\Events\NonExistentHandler',
        ]);

        $classesToScan = $scanner->getClassesToScan();
        $this->assertCount(1, $classesToScan);
        $this->assertEquals('App\Handlers\Events\BasicEventHandler', reset($classesToScan)->name);
    }
}
