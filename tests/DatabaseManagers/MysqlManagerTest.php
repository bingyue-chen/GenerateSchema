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

    public function testGetConnectionNameSuccess()
    {
        $mysql_manager = $this->app->make(MysqlManager::class);

        $connection_name = $mysql_manager->getConnectionName();

        $this->assertEquals('mysql', $connection_name);
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
        $this->assertArrayHasKey('posts', $schema);

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

        $this->assertArraySubset([
            [
                'name'            => 'id',
                'type'            => 'int(10) unsigned',
                'key'             => 'PRI',
                'nullable'        => 'NO',
                'default'         => null,
                'constraint_name' => '',
                'referenced'      => '',
            ],
            [
                'name'            => 'name',
                'type'            => 'varchar(255)',
                'key'             => '',
                'nullable'        => 'NO',
                'default'         => null,
                'constraint_name' => '',
                'referenced'      => '',
            ],
            [
                'name'            => 'email',
                'type'            => 'varchar(255)',
                'key'             => 'UNI',
                'nullable'        => 'NO',
                'default'         => null,
                'constraint_name' => 'users_email_unique',
                'referenced'      => '',
            ],
            [
                'name'            => 'password',
                'type'            => 'varchar(255)',
                'key'             => '',
                'nullable'        => 'NO',
                'default'         => null,
                'constraint_name' => '',
                'referenced'      => '',
            ],
            [
                'name'            => 'remember_token',
                'type'            => 'varchar(100)',
                'key'             => '',
                'nullable'        => 'YES',
                'default'         => null,
                'constraint_name' => '',
                'referenced'      => '',
            ],
            [
                'name'            => 'created_at',
                'type'            => 'timestamp',
                'key'             => '',
                'nullable'        => 'YES',
                'default'         => null,
                'constraint_name' => '',
                'referenced'      => '',
            ],
            [
                'name'            => 'updated_at',
                'type'            => 'timestamp',
                'key'             => '',
                'nullable'        => 'YES',
                'default'         => null,
                'constraint_name' => '',
                'referenced'      => '',
            ],
        ], $schema['users']);

        $this->assertArraySubset([
            [
                'name'            => 'email',
                'type'            => 'varchar(255)',
                'key'             => 'MUL',
                'nullable'        => 'NO',
                'default'         => null,
                'constraint_name' => '',
                'referenced'      => '',
            ],
            [
                'name'            => 'token',
                'type'            => 'varchar(255)',
                'key'             => '',
                'nullable'        => 'NO',
                'default'         => null,
                'constraint_name' => '',
                'referenced'      => '',
            ],
            [
                'name'            => 'created_at',
                'type'            => 'timestamp',
                'key'             => '',
                'nullable'        => 'YES',
                'default'         => null,
                'constraint_name' => '',
                'referenced'      => '',
            ],
        ], $schema['password_resets']);

        $this->assertArraySubset([
            [
                'name'            => 'id',
                'type'            => 'int(10) unsigned',
                'key'             => 'PRI',
                'nullable'        => 'NO',
                'default'         => null,
                'constraint_name' => '',
                'referenced'      => '',
            ],
            [
                'name'            => 'user_id',
                'type'            => 'int(10) unsigned',
                'key'             => 'MUL',
                'nullable'        => 'NO',
                'default'         => null,
                'constraint_name' => 'posts_user_id_foreign',
                'referenced'      => 'users.id',
            ],
            [
                'name'            => 'title',
                'type'            => 'varchar(255)',
                'key'             => '',
                'nullable'        => 'NO',
                'default'         => null,
                'constraint_name' => '',
                'referenced'      => '',
            ],
            [
                'name'            => 'content',
                'type'            => 'varchar(255)',
                'key'             => '',
                'nullable'        => 'NO',
                'default'         => null,
                'constraint_name' => '',
                'referenced'      => '',
            ],
            [
                'name'            => 'created_at',
                'type'            => 'timestamp',
                'key'             => '',
                'nullable'        => 'YES',
                'default'         => null,
                'constraint_name' => '',
                'referenced'      => '',
            ],
            [
                'name'            => 'updated_at',
                'type'            => 'timestamp',
                'key'             => '',
                'nullable'        => 'YES',
                'default'         => null,
                'constraint_name' => '',
                'referenced'      => '',
            ],
        ], $schema['posts']);
    }
}
