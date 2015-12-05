<?php

namespace Collective\Annotations\Console;

use Collective\Annotations\AnnotationsServiceProvider;
use Collective\Annotations\Events\Annotations\Scanner;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;

class EventScanCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'event:scan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan a directory for event annotations';

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
        $this->files->put($this->getOutputPath(), $this->getEventDefinitions());

        $this->info('Events scanned!');
    }

    /**
     * Get the route definitions for the annotations.
     *
     * @return string
     */
    protected function getEventDefinitions()
    {
        $scanner = $this->laravel->make('annotations.event.scanner');

        $scanner->setClassesToScan($this->provider->eventScans());

        return '<?php '.PHP_EOL.PHP_EOL.$scanner->getEventDefinitions().PHP_EOL;
    }

    /**
     * Get the path to which the routes should be written.
     *
     * @return string
     */
    protected function getOutputPath()
    {
        return $this->laravel['path.storage'].'/framework/events.scanned.php';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
          ['path', null, InputOption::VALUE_OPTIONAL, 'The path to scan.'],
        ];
    }
}
