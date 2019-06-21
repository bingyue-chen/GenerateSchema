<?php

namespace Snowcookie\GenerateSchema\Test\DatabaseManagers;

use Snowcookie\GenerateSchema\DatabaseManagers\PostgresManager;

class PostgresManagerTest extends TestDatabaseManagerCase
{
    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('database.default', 'pgsql');
    }

    public function testGetConnectionNameSuccess()
    {
        $mysql_manager = $this->app->make(PostgresManager::class);

        $connection_name = $mysql_manager->getConnectionName();

        $this->assertEquals('pgsql', $connection_name);
    }

    public function testGetAllTableNameSuccess()
    {
        $pgsql_manager = $this->app->make(PostgresManager::class);

        $tables = $pgsql_manager->getAllTableName($this->database_name);

        $this->assertContains('migrations', $tables);
        $this->assertContains('users', $tables);
        $this->assertContains('password_resets', $tables);
        $this->assertContains('posts', $tables);
    }

    public function testGetEachTableColumnTypeSuccess()
    {
        $pgsql_manager = $this->app->make(PostgresManager::class);

        $expected_schema_struct = [
            'migrations' => [
                [
                    'name'            => 'id',
                    'type'            => 'integer',
                    'key'             => 'PRI',
                    'nullable'        => 'NO',
                    'default'         => "nextval('migrations_id_seq'::regclass)",
                    'constraint_name' => '',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'migration',
                    'type'            => 'character varying(255)',
                    'key'             => '',
                    'nullable'        => 'NO',
                    'default'         => null,
                    'constraint_name' => '',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'batch',
                    'type'            => 'integer',
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
                    'type'            => 'integer',
                    'key'             => 'PRI',
                    'nullable'        => 'NO',
                    'default'         => "nextval('users_id_seq'::regclass)",
                    'constraint_name' => '',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'name',
                    'type'            => 'character varying(255)',
                    'key'             => '',
                    'nullable'        => 'NO',
                    'default'         => null,
                    'constraint_name' => '',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'email',
                    'type'            => 'character varying(255)',
                    'key'             => '',
                    'nullable'        => 'NO',
                    'default'         => null,
                    'constraint_name' => 'users_email_unique',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'password',
                    'type'            => 'character varying(255)',
                    'key'             => '',
                    'nullable'        => 'NO',
                    'default'         => null,
                    'constraint_name' => '',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'remember_token',
                    'type'            => 'character varying(100)',
                    'key'             => '',
                    'nullable'        => 'YES',
                    'default'         => null,
                    'constraint_name' => '',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'created_at',
                    'type'            => 'timestamp without time zone',
                    'key'             => '',
                    'nullable'        => 'YES',
                    'default'         => null,
                    'constraint_name' => '',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'updated_at',
                    'type'            => 'timestamp without time zone',
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
                    'type'            => 'character varying(255)',
                    'key'             => '',
                    'nullable'        => 'NO',
                    'default'         => null,
                    'constraint_name' => '',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'token',
                    'type'            => 'character varying(255)',
                    'key'             => '',
                    'nullable'        => 'NO',
                    'default'         => null,
                    'constraint_name' => '',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'created_at',
                    'type'            => 'timestamp without time zone',
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
                    'type'            => 'integer',
                    'key'             => 'PRI',
                    'nullable'        => 'NO',
                    'default'         => "nextval('posts_id_seq'::regclass)",
                    'constraint_name' => '',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'user_id',
                    'type'            => 'integer',
                    'key'             => '',
                    'nullable'        => 'NO',
                    'default'         => null,
                    'constraint_name' => 'posts_user_id_foreign',
                    'referenced'      => 'users.id',
                ],
                [
                    'name'            => 'title',
                    'type'            => 'character varying(255)',
                    'key'             => '',
                    'nullable'        => 'NO',
                    'default'         => null,
                    'constraint_name' => '',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'content',
                    'type'            => 'character varying(255)',
                    'key'             => '',
                    'nullable'        => 'NO',
                    'default'         => null,
                    'constraint_name' => '',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'created_at',
                    'type'            => 'timestamp without time zone',
                    'key'             => '',
                    'nullable'        => 'YES',
                    'default'         => null,
                    'constraint_name' => '',
                    'referenced'      => '',
                ],
                [
                    'name'            => 'updated_at',
                    'type'            => 'timestamp without time zone',
                    'key'             => '',
                    'nullable'        => 'YES',
                    'default'         => null,
                    'constraint_name' => '',
                    'referenced'      => '',
                ],
            ],
        ];

        $actual_schema_struct = $pgsql_manager->getEachTableColumnType($this->database_name, ['migrations', 'users', 'password_resets', 'posts']);

        $this->assertSchemaStruct($expected_schema_struct, $actual_schema_struct);
    }
}
