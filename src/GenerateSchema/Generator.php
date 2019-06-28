<?php

namespace Snowcookie\GenerateSchema;

use Snowcookie\GenerateSchema\Contracts\GeneratorDatabaseManager;
use Snowcookie\GenerateSchema\Contracts\GeneratorRenderer;
use Snowcookie\GenerateSchema\DatabaseManagers\MysqlManager;
use Snowcookie\GenerateSchema\DatabaseManagers\PostgresManager;
use Snowcookie\GenerateSchema\Renderers\TxtRenderer;

class Generator
{
    private $database_name;
    private $database_tables;
    private $database_manager;

    //[
    //  table_name => [
    //      name,
    //      type,
    //      key,
    //      nullable,
    //      default,
    //      constraint_name,
    //      referenced
    //  ]
    //]
    //
    private $schmea_struct;
    private $renderer;

    public function __construct(GeneratorDatabaseManager $database_manager = null, GeneratorRenderer $renderer = null)
    {
        if (null !== $database_manager) {
            $this->setDatabaseManager($database_manager);
        } else {
            $this->resolveDatabaseManager();
        }

        if (null !== $renderer) {
            $this->setRenderer($renderer);
        } else {
            $this->setDefaultRenderer();
        }

        $this->database_name   = '';
        $this->database_tables = [];
        $this->schmea_struct   = [];
    }

    public function generateSchema(string $database_name = ''): self
    {
        $this->setDataBase($database_name);
        $this->getAllTableName();
        $this->getEachTableColumnType();

        return $this;
    }

    public function getSchema(): array
    {
        return $this->schmea_struct;
    }

    public function render($storage_disk): bool
    {
        return $this->renderer->render($storage_disk, $this->database_name, $this->schmea_struct);
    }

    public function setDefaultRenderer(): self
    {
        $this->renderer = app()->make(TxtRenderer::class);

        return $this;
    }

    public function setRenderer(GeneratorRenderer $renderer): self
    {
        $this->renderer = $renderer;

        return $this;
    }

    public function setDatabaseManager(GeneratorDatabaseManager $database_manager): self
    {
        $this->database_manager = $database_manager;

        return $this;
    }

    private function resolveDatabaseManager()
    {
        $connection = config('database.default');

        $database_manager_class = ([
            'mysql' => MysqlManager::class,
            'pgsql' => PostgresManager::class,
        ])[$connection];

        $this->database_manager = app()->make($database_manager_class);
    }

    private function setDataBase(string $database_name = '')
    {
        $this->database_name = $database_name ?: config('database.connections.'.$this->database_manager->getConnectionName().'.database');
    }

    private function getAllTableName()
    {
        $this->database_tables = $this->database_manager->getAllTableName($this->database_name);
    }

    private function getEachTableColumnType()
    {
        $this->schmea_struct = $this->database_manager->getEachTableColumnType($this->database_name, $this->database_tables);
    }
}
