<?php

namespace Collective\Annotations\Console;

use Collective\Annotations\AnnotationsServiceProvider;
use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;

class RouteScanCommand extends Command
{
    use DetectsApplicationNamespace;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'route:scan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan a directory for controller annotations';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The Service Provider instance.
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

        $this->info('Routes scanned!');

        if ($this->option('list')) {
            $this->call('route:list');
        }
    }

    /**
     * Get the route definitions for the annotations.
     *
     * @return string
     */
    protected function getRouteDefinitions()
    {
        $scanner = $this->laravel->make('annotations.route.scanner');

        $scanner->setClassesToScan($this->provider->routeScans());

        return '<?php '.PHP_EOL.PHP_EOL.$scanner->getRouteDefinitions().PHP_EOL;
    }

    /**
     * Get the path to which the routes should be written.
     *
     * @return string
     */
    protected function getOutputPath()
    {
        return $this->laravel['path.storage'].'/framework/routes.scanned.php';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $namespace = $this->getAppNamespace().'Http\Controllers';

        return [
          ['namespace', null, InputOption::VALUE_OPTIONAL, 'The root namespace for the controllers.', $namespace],

          ['path', null, InputOption::VALUE_OPTIONAL, 'The path to scan.', 'Http'.DIRECTORY_SEPARATOR.'Controllers'],

          ['list', null, InputOption::VALUE_NONE, 'List all registered routes'],
        ];
    }
}
