<?php

namespace Snowcookie\GenerateSchema\Test\DatabaseManagers;

use Snowcookie\GenerateSchema\DatabaseManagers\MysqlManager;
use Snowcookie\GenerateSchema\Test\TestCase;

class MysqlManagerTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->refreshDatabase();
    }

    public function testGetConnectionSuccess()
    {
        $mysql_manager = $this->app->make(MysqlManager::class);

        $connection = $mysql_manager->getConnection();

        $this->assertEquals('mysql', $connection);
    }

    public function testGetAllTableNameSuccess()
    {
        $mysql_manager = $this->app->make(MysqlManager::class);

        $tables = $mysql_manager->getAllTableName($this->database_name);

        $this->assertContains('migrations', $tables);
        $this->assertContains('users', $tables);
        $this->assertContains('password_resets', $tables);
        $this->assertContains('posts', $tables);
    }

    public function testGetEachTableColumnTypeSuccess()
    {
        $mysql_manager = $this->app->make(MysqlManager::class);

        $schema = $mysql_manager->getEachTableColumnType($this->database_name, ['migrations', 'users', 'password_resets', 'posts']);

        $this->assertArrayHasKey('migrations', $schema);
        $this->assertArrayHasKey('users', $schema);
        $this->assertArrayHasKey('password_resets', $schema);

        $this->assertArraySubset([
            [
                'name'            => 'id',
                'type'            => 'int(10) unsigned',
                'key'             => 'PRI',
                'nullable'        => 'NO',
                'default'         => '',
                'constraint_name' => '',
                'referenced'      => '',
            ],
            [
                'name'            => 'migration',
                'type'            => 'varchar(255)',
                'key'             => '',
                'nullable'        => 'NO',
                'default'         => null,
                'constraint_name' => '',
                'referenced'      => '',
            ],
            [
                'name'            => 'batch',
                'type'            => 'int(11)',
                'key'             => '',
                'nullable'        => 'NO',
                'default'         => null,
                'constraint_name' => '',
                'referenced'      => '',
            ],
        ], $schema['migrations']);
    }
}
