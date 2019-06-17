<?php

namespace Snowcookie\GenerateSchema\Test;

use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    protected function refreshDatabase()
    {
        $this->artisan('migrate:refresh', ['--path' => '../../../../tests/migrations']);
    }
}
