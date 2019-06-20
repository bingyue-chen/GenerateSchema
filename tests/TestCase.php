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

    protected function refreshDatabase()
    {
        $this->artisan('migrate:refresh', ['--path' => '../../../../tests/migrations']);
    }
}
