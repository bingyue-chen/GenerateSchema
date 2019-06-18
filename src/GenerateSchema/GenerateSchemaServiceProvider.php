<?php

namespace Snowcookie\GenerateSchema;

use Illuminate\Support\ServiceProvider;
use Snowcookie\GenerateSchema\Commands\GenerateSchemaCommand;

class GenerateSchemaServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/Configs/generate-schema-command.php' => config_path('generate-schema-command.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateSchemaCommand::class,
            ]);
        }
    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/Configs/generate-schema-command.php',
            'generate-schema-command'
        );
    }
}
