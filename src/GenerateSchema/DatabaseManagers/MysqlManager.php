<?php

namespace Snowcookie\GenerateSchema\DatabaseManagers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Snowcookie\GenerateSchema\Contracts\GeneratorDatabaseManager;

class MysqlManager implements GeneratorDatabaseManager
{
    protected $connection_name = 'mysql';
    protected $connection      = null;

    public function __construct()
    {
        $this->connection = DB::connection($this->connection_name);
    }

    public function getConnectionName(): string
    {
        return $this->connection_name;
    }

    public function getAllTableName(string $database_name): array
    {
        return $this->connection
            ->table('information_schema.tables')
            ->select(['table_name'])
            ->where('table_schema', $database_name)
            ->get()
            ->pluck('TABLE_NAME')
            ->all();
    }

    public function getEachTableColumnType(string $database_name, array $database_tables): array
    {
        $schmea_struct = [];

        foreach ($database_tables as $table_name) {
            $schmea_struct[$table_name] = [];

            $columns_describe = $this->getColumnDescribe($database_name, $table_name);

            $columns_unique = $this->getColumnUnique($database_name, $table_name);

            $columns_foreign = $this->getColumnForeign($database_name, $table_name);

            foreach ($columns_describe as $column_describe) {
                $is_unique  = $columns_unique->has($column_describe->COLUMN_NAME);
                $is_foreign = $columns_foreign->has($column_describe->COLUMN_NAME);

                $constraint_names   = [];
                $constraint_names[] = $is_unique ? $columns_unique->get($column_describe->COLUMN_NAME)->constraint_name : '';
                $constraint_names[] = $is_foreign ? $columns_foreign->get($column_describe->COLUMN_NAME)->constraint_name : '';

                $constraint_name = collect($constraint_names)->filter()->implode(',');

                $referenced = $is_foreign ? implode('.', [$columns_foreign->get($column_describe->COLUMN_NAME)->referenced_table_name, $columns_foreign->get($column_describe->COLUMN_NAME)->referenced_column_name]) : '';

                $schmea_struct[$table_name][] = [
                    'name'            => $column_describe->COLUMN_NAME,
                    'type'            => $column_describe->COLUMN_TYPE,
                    'key'             => $column_describe->COLUMN_KEY,
                    'nullable'        => $column_describe->IS_NULLABLE,
                    'default'         => $column_describe->COLUMN_DEFAULT,
                    'constraint_name' => $constraint_name,
                    'referenced'      => $referenced,
                ];
            }
        }

        return $schmea_struct;
    }

    protected function getColumnDescribe(string $database_name, string $table_name): Collection
    {
        return $this->connection
            ->table('information_schema.columns')
            ->select([
                'column_name',
                'column_type',
                'column_key',
                'is_nullable',
                'column_default',
            ])
            ->where('table_schema', $database_name)
            ->where('table_name', $table_name)
            ->orderBy('ordinal_position', 'asc')
            ->get();
    }

    protected function getColumnUnique(string $database_name, string $table_name): Collection
    {
        return $this->connection
            ->table('information_schema.key_column_usage')
            ->join('information_schema.table_constraints', function ($query) {
                $query->on('information_schema.key_column_usage.constraint_name', '=', 'information_schema.table_constraints.constraint_name')
                    ->where('information_schema.table_constraints.constraint_type', 'UNIQUE');
            })
            ->where('information_schema.key_column_usage.table_schema', $database_name)
            ->where('information_schema.key_column_usage.table_name', $table_name)
            ->select([
                'information_schema.key_column_usage.column_name',
                'information_schema.key_column_usage.constraint_name',
            ])
            ->get()
            ->keyBy('column_name');
    }

    protected function getColumnForeign(string $database_name, string $table_name): Collection
    {
        return $this->connection
            ->table('information_schema.key_column_usage')
            ->join('information_schema.table_constraints', function ($query) {
                $query->on('information_schema.key_column_usage.constraint_name', '=', 'information_schema.table_constraints.constraint_name')
                    ->where('information_schema.table_constraints.constraint_type', 'FOREIGN KEY');
            })
            ->where('information_schema.key_column_usage.table_schema', $database_name)
            ->where('information_schema.key_column_usage.table_name', $table_name)
            ->select([
                'information_schema.key_column_usage.column_name',
                'information_schema.key_column_usage.referenced_table_name',
                'information_schema.key_column_usage.referenced_column_name',
                'information_schema.key_column_usage.constraint_name',
            ])
            ->get()
            ->keyBy('column_name');
    }
}
