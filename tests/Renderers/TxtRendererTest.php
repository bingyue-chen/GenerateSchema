<?php

namespace Snowcookie\GenerateSchema\Test\Renderers;

use Illuminate\Support\Facades\Storage;
use Snowcookie\GenerateSchema\Renderers\TxtRenderer;
use Snowcookie\GenerateSchema\Test\TestCase;

class TxtRendererTest extends TestCase
{
    protected $root_path = '';

    protected function setUp()
    {
        parent::setUp();
    }

    public function testRenderSuccess()
    {
        $txt_render  = $this->app->make(TxtRenderer::class);
        $driver_name = 'shema';

        Storage::fake($driver_name);

        $render_response = $txt_render->render($driver_name, 'homestead', ['migrations' => [[
                'name'            => 'id',
                'type'            => 'int(10) unsigned',
                'key'             => 'PRI',
                'nullable'        => 'NO',
                'default'         => '',
                'constraint_name' => '',
                'referenced'      => '',
            ]]]);

        Storage::disk($driver_name)->assertExists('migrations.txt');
    }
}
