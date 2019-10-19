<?php

namespace Snowcookie\GenerateSchema\Test\Renderers;

use ErrorException;
use Exception;
use Illuminate\Support\Facades\Storage;
use Snowcookie\GenerateSchema\Renderers\TxtRenderer;
use Snowcookie\GenerateSchema\Test\TestCase;

class TxtRendererTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testRenderSuccess()
    {
        $txt_render = $this->app->make(TxtRenderer::class);
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

        Storage::disk($disk_name)->assertExists('migrations.txt');
    }

    public function testRenderFailCleanFileSuccess()
    {
        $txt_render = $this->app->make(TxtRenderer::class);
        $disk_name  = 'schema';

        Storage::fake($disk_name);

        $this->expectException(ErrorException::class);

        try {
            $txt_render->render($disk_name, 'homestead', [
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
                'users' => [
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
                'images' => [
                ],
            ]);
        } catch (Exception $exception) {
            Storage::disk($disk_name)->assertMissing('migrations.csv');
            Storage::disk($disk_name)->assertMissing('users.csv');
            throw $exception;
        }
    }
}
