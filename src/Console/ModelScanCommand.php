<?php namespace Collective\Annotations\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Collective\Annotations\Routing\Annotations\Scanner;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Console\AppNamespaceDetectorTrait;

class ModelScanCommand extends Command {

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
     * Create a new event scan command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
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
        $provider = 'Collective\Annotations\AnnotationsServiceProvider';

        $scanner = $this->laravel->make('annotations.model.scanner');

        $scanner->setClassesToScan($this->laravel->getProvider($provider)->modelScans());

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
