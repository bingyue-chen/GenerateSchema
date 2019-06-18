<?php

namespace Snowcookie\GenerateSchema\Test;

use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected $database_name = 'homestead';

    protected function setUp()
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
        $app['config']->set('database.default', 'mysql');
        $app['config']->set('database.connections.mysql', [
            'driver'    => 'mysql',
            'host'      => 'snowcookie-generate-schema-mysql',
            'database'  => $this->database_name,
            'username'  => 'homestead',
            'password'  => 'secret',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'strict'    => false,
        ]);
    }

    protected function refreshDatabase()
    {
        $this->artisan('migrate:refresh', ['--path' => '../../../../tests/migrations']);
    }
}
