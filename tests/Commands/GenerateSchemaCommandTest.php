<?php

namespace Snowcookie\GenerateSchema\Test\Commands;

use Illuminate\Support\Facades\Storage;
use Snowcookie\GenerateSchema\Test\TestCase;

class GenerateSchemaCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->refreshDatabase();
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'mysql');
        $app['config']->set('database.connections.mysql', [
            'driver'    => 'mysql',
            'host'      => 'snowcookie-generate-schema-mysql',
            'port'      => '3306',
            'database'  => $this->database_name,
            'username'  => 'homestead',
            'password'  => 'secret',
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'strict'    => true,
            'engine'    => null,
        ]);
    }

    protected function getPackageProviders($app)
    {
        return ['Snowcookie\GenerateSchema\GenerateSchemaServiceProvider'];
    }

    public function testDefaultGenerateSuccess()
    {
        $disk_name = 'schema';

        Storage::fake($disk_name);

        $this->artisan('tools:generate_schema', ['--storage_disk' => $disk_name]);

        Storage::disk($disk_name)->assertExists('migrations.txt');
    }
}
