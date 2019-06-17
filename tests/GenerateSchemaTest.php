<?php

namespace Snowcookie\GenerateSchema\Test;

use Mockery;
use Snowcookie\GenerateSchema\Contracts\GeneratorDatabaseManager;
use Snowcookie\GenerateSchema\Contracts\GeneratorRenderer;
use Snowcookie\GenerateSchema\Generator;

class GenerateSchemaTest extends TestCase
{
    public function testGetSchemaSuccess()
    {
        $mock_database_manager = Mockery::mock(GeneratorDatabaseManager::class);
        $mock_renderer         = Mockery::mock(GeneratorRenderer::class);

        $mock_database_manager->shouldReceive('getConnection')->times(1)->andReturn('mysql');
        $mock_database_manager->shouldReceive('getAllTableName')->times(1)->andReturn(['migrations', 'users', 'password_resets', 'posts']);
        $mock_database_manager->shouldReceive('getEachTableColumnType')->times(1)->andReturn(['migrations' => [], 'users' => [], 'password_resets' => [], 'posts' => []]);

        $generator = $this->app->makeWith(Generator::class, [
            'database_manager' => $mock_database_manager,
            'renderer'         => $mock_renderer,
        ]);

        $schema = $generator->generateSchema()->getSchema();

        $this->assertArrayHasKey('migrations', $schema);
        $this->assertArrayHasKey('users', $schema);
        $this->assertArrayHasKey('password_resets', $schema);
        $this->assertArrayHasKey('posts', $schema);
    }

    public function testRenderSuccess()
    {
        $mock_database_manager = Mockery::mock(GeneratorDatabaseManager::class);
        $mock_renderer         = Mockery::mock(GeneratorRenderer::class);

        $mock_renderer->shouldReceive('render')->times(1)->andReturn(true);

        $generator = $this->app->makeWith(Generator::class, [
            'database_manager' => $mock_database_manager,
            'renderer'         => $mock_renderer,
        ]);

        $render_response = $generator->render('');

        $this->assertEquals(true, $render_response);
    }
}
