<?php

namespace Snowcookie\GenerateSchema\Test\DatabaseManagers;

use Snowcookie\GenerateSchema\DatabaseManagers\MysqlManager;

class MysqlManagerTest extends TestDatabaseManagerCase
{
    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('database.default', 'mysql');
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
                    'index_name'      => 'PRIMARY',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'migration',
                    'type'            => 'varchar(255)',
                    'key'             => '',
                    'nullable'        => 'NO',
                    'default'         => null,
                    'constraint_name' => '',
                    'index_name'      => '',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'batch',
                    'type'            => 'int(11)',
                    'key'             => '',
                    'nullable'        => 'NO',
                    'default'         => null,
                    'constraint_name' => '',
                    'index_name'      => '',
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
                    'index_name'      => 'PRIMARY',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'name',
                    'type'            => 'varchar(255)',
                    'key'             => '',
                    'nullable'        => 'NO',
                    'default'         => null,
                    'constraint_name' => '',
                    'index_name'      => '',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'email',
                    'type'            => 'varchar(255)',
                    'key'             => 'UNI',
                    'nullable'        => 'NO',
                    'default'         => null,
                    'constraint_name' => 'users_email_unique',
                    'index_name'      => 'users_email_unique',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'password',
                    'type'            => 'varchar(255)',
                    'key'             => '',
                    'nullable'        => 'NO',
                    'default'         => null,
                    'constraint_name' => '',
                    'index_name'      => '',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'remember_token',
                    'type'            => 'varchar(100)',
                    'key'             => '',
                    'nullable'        => 'YES',
                    'default'         => null,
                    'constraint_name' => '',
                    'index_name'      => '',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'created_at',
                    'type'            => 'timestamp',
                    'key'             => '',
                    'nullable'        => 'YES',
                    'default'         => null,
                    'constraint_name' => '',
                    'index_name'      => '',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'updated_at',
                    'type'            => 'timestamp',
                    'key'             => '',
                    'nullable'        => 'YES',
                    'default'         => null,
                    'constraint_name' => '',
                    'index_name'      => '',
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
                    'index_name'      => 'password_resets_email_index',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'token',
                    'type'            => 'varchar(255)',
                    'key'             => '',
                    'nullable'        => 'NO',
                    'default'         => null,
                    'constraint_name' => '',
                    'index_name'      => '',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'created_at',
                    'type'            => 'timestamp',
                    'key'             => '',
                    'nullable'        => 'YES',
                    'default'         => null,
                    'constraint_name' => '',
                    'index_name'      => '',
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
                    'index_name'      => 'PRIMARY',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'user_id',
                    'type'            => 'int(10) unsigned',
                    'key'             => 'MUL',
                    'nullable'        => 'NO',
                    'default'         => null,
                    'constraint_name' => 'posts_user_id_foreign',
                    'index_name'      => 'posts_user_id_foreign',
                    'referenced'      => 'users.id on update CASCADE on delete CASCADE',
                ],
                [
                    'name'            => 'title',
                    'type'            => 'varchar(255)',
                    'key'             => '',
                    'nullable'        => 'NO',
                    'default'         => null,
                    'constraint_name' => '',
                    'index_name'      => '',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'content',
                    'type'            => 'varchar(255)',
                    'key'             => '',
                    'nullable'        => 'NO',
                    'default'         => null,
                    'constraint_name' => '',
                    'index_name'      => '',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'created_at',
                    'type'            => 'timestamp',
                    'key'             => '',
                    'nullable'        => 'YES',
                    'default'         => null,
                    'constraint_name' => '',
                    'index_name'      => '',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'updated_at',
                    'type'            => 'timestamp',
                    'key'             => '',
                    'nullable'        => 'YES',
                    'default'         => null,
                    'constraint_name' => '',
                    'index_name'      => '',
                    'referenced'      => '',
                ],
            ],
        ];

        $actual_schema_struct = $mysql_manager->getEachTableColumnType($this->database_name, ['migrations', 'users', 'password_resets', 'posts']);

        $this->assertSchemaStruct($expected_schema_struct, $actual_schema_struct);
    }
}
