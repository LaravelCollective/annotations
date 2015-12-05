<?php

namespace Collective\Annotations\Console;

use Collective\Annotations\AnnotationsServiceProvider;
use Collective\Annotations\Routing\Annotations\Scanner;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ModelScanCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'model:scan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan a directory for model annotations';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The Serivce Provider instance.
     *
     * @var AnnotationsServiceProvider
     */
    protected $provider;

    /**
     * Create a new event scan command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @param AnnotationsServiceProvider        $provider
     */
    public function __construct(Filesystem $files, AnnotationsServiceProvider $provider)
    {
        parent::__construct();

        $this->files = $files;
        $this->provider = $provider;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->files->put($this->getOutputPath(), $this->getRouteDefinitions());

        $this->info('Models scanned!');
    }

    /**
     * Get the route definitions for the annotations.
     *
     * @return string
     */
    protected function getRouteDefinitions()
    {
        $scanner = $this->laravel->make('annotations.model.scanner');

        $scanner->setClassesToScan($this->provider->modelScans());

        return '<?php '.PHP_EOL.PHP_EOL.$scanner->getModelDefinitions().PHP_EOL;
    }

    /**
     * Get the path to which the routes should be written.
     *
     * @return string
     */
    protected function getOutputPath()
    {
        return $this->laravel['path.storage'].'/framework/models.scanned.php';
    }
}
