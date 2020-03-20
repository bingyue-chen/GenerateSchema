<?php

namespace Snowcookie\GenerateSchema\Commands;

use Illuminate\Console\Command;
use Snowcookie\GenerateSchema\Generator;

class GenerateSchemaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tools:generate_schema_command
                            {--D|database= : Whether the database should be generated}
                            {--S|storage_disk= : Whether the storage should be schema render to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate schema from database';

    protected $config;
    protected $database_manager;
    protected $renderer;
    protected $generator;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->getConfig();
        $this->getDatabaseManager();
        $this->getRenderer();
        $this->getGenerator();

        $this->generate();
    }

    protected function getConfig()
    {
        $this->config = config('generate-schema-command');
    }

    protected function getDatabaseManager()
    {
        $this->database_manager = null;

        if (!$this->config['use_default']) {
            $this->database_manager = app()->make($this->config['database_manager']);
        }
    }

    protected function getRenderer()
    {
        $this->renderer = null;

        if (!$this->config['use_default']) {
            $this->renderer = app()->make($this->config['renderer']);
        }
    }

    protected function getGenerator()
    {
        $this->generator = app()->makeWith(Generator::class, [
            'database_manager' => $this->database_manager,
            'renderer'         => $this->renderer,
        ]);
    }

    protected function generate()
    {
        $database     = $this->option('database')     ?? '';
        $storage_disk = $this->option('storage_disk') ?? '';

        $this->generator->generateSchema($database)->render($storage_disk);

        $this->info('Generate schema compelete.');
    }
}
