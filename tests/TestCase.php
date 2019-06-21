<?php

namespace Snowcookie\GenerateSchema\Test;

use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected $database_name = 'homestead';

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.connections.mysql', [
            'driver'    => 'mysql',
            'host'      => env('MYSQL_HOST', '127.0.0.1'),
            'port'      => env('MYSQL_PORT', '3306'),
            'database'  => $this->database_name,
            'username'  => 'homestead',
            'password'  => 'secret',
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'strict'    => true,
            'engine'    => null,
        ]);

        $app['config']->set('database.connections.pgsql', [
            'driver'   => 'pgsql',
            'host'     => env('POSTGRES_HOST', '127.0.0.1'),
            'port'     => env('POSTGRES_PORT', '5432'),
            'database' => $this->database_name,
            'username' => 'homestead',
            'password' => 'secret',
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => 'public',
            'sslmode'  => 'prefer',
        ]);
    }

    protected function refreshDatabase()
    {
        $this->artisan('migrate:refresh', ['--path' => '../../../../tests/migrations']);
    }
}
