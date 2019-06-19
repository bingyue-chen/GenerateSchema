<?php

namespace Snowcookie\GenerateSchema\Test\DatabaseManagers;

use Snowcookie\GenerateSchema\DatabaseManagers\MysqlManager;
use Snowcookie\GenerateSchema\Test\TestCase;

class MysqlManagerTest extends TestCase
{
    protected function setUp(): void
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

        $expected_schema_struct = [
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
            ],
            'users' => [
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
            ],
            'password_resets' => [
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
            ],
            'posts' => [
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
            ],
        ];

        $actual_schema_struct = $mysql_manager->getEachTableColumnType($this->database_name, ['migrations', 'users', 'password_resets', 'posts']);

        $this->assertSchemaStruct($expected_schema_struct, $actual_schema_struct);
    }

    protected function assertSchemaStruct($expected_schema_struct, $actual_schema_struct)
    {
        foreach ($expected_schema_struct as $expected_table_name => $expected_table_struct) {
            $this->assertArrayHasKey($expected_table_name, $actual_schema_struct);
            $this->assertTableStruct($expected_table_struct, $actual_schema_struct[$expected_table_name]);
        }
    }

    protected function assertTableStruct($expected_table_struct, $actual_table_struct)
    {
        foreach ($expected_table_struct as $expected_column_index => $expected_column_struct) {
            $this->assertArrayHasKey($expected_column_index, $actual_table_struct);
            $this->assertColumnStruct($expected_column_struct, $actual_table_struct[$expected_column_index]);
        }
    }

    protected function assertColumnStruct($expected_column_struct, $actual_colmun_struct)
    {
        foreach ($expected_column_struct as $expected_column_field => $expected_column_value) {
            $this->assertArrayHasKey($expected_column_field, $actual_colmun_struct);
            $this->assertEquals($expected_column_value, $actual_colmun_struct[$expected_column_field]);
        }
    }
}
