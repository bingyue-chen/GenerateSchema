<?php

namespace Snowcookie\GenerateSchema\Test\Renderers;

use Illuminate\Support\Facades\Storage;
use Snowcookie\GenerateSchema\Renderers\CsvRenderer;
use Snowcookie\GenerateSchema\Test\TestCase;

class CsvRendererTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testRenderSuccess()
    {
        $txt_render = $this->app->make(CsvRenderer::class);
        $disk_name  = 'schema';

        Storage::fake($disk_name);

        $render_response = $txt_render->render($disk_name, 'homestead', [
            'migrations' => [
                [
                    'name'            => 'id',
                    'type'            => 'int(10) unsigned',
                    'key'             => 'PRI',
                    'nullable'        => 'NO',
                    'default'         => '',
                    'constraint_name' => '',
                    'referenced'      => '',
                ],
            ],
        ]);

        Storage::disk($disk_name)->assertExists('migrations.csv');
    }
}
