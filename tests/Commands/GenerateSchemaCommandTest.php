<?php

namespace Snowcookie\GenerateSchema\Test\Commands;

use Illuminate\Support\Facades\Storage;
use Snowcookie\GenerateSchema\Test\TestCase;

class GenerateSchemaCommandTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->refreshDatabase();
    }

    protected function getPackageProviders($app)
    {
        return ['Snowcookie\GenerateSchema\GenerateSchemaServiceProvider'];
    }

    public function testDefaultGenerateSuccess()
    {
        $disk_name = 'schema';

        Storage::fake($disk_name);

        $this->artisan('tool:generate_schema_command', ['--storage_disk' => $disk_name]);

        Storage::disk($disk_name)->assertExists('migrations.txt');
    }
}
