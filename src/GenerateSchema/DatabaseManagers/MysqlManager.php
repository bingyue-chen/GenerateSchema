<?php

namespace Snowcookie\GenerateSchema\DatabaseManagers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Snowcookie\GenerateSchema\Contracts\GeneratorDatabaseManager;

class MysqlManager implements GeneratorDatabaseManager
{
    protected $connection_name = 'mysql';
    protected $connection      = null;

    public function __construct(string $connection_name = 'mysql')
    {
        $this->connection_name = $connection_name;
        $this->connection      = DB::connection($this->connection_name);
    }

    public function getConnectionName(): string
    {
        return $this->connection_name;
    }

    public function getAllTableName(string $database_name): array
    {
        return $this->connection
            ->table('information_schema.tables')
            ->select(['table_name as table_name'])
            ->where('table_schema', $database_name)
            ->where('table_type', 'BASE TABLE')
            ->get()
            ->pluck('table_name')
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

            $columns_index = $this->getColumnIndex($database_name, $table_name);

            foreach ($columns_describe as $column_describe) {
                $is_unique  = $columns_unique->has($column_describe->column_name);
                $is_foreign = $columns_foreign->has($column_describe->column_name);

                $constraint_names   = [];
                $constraint_names[] = $is_unique ? $columns_unique->get($column_describe->column_name)->constraint_name : '';
                $constraint_names[] = $is_foreign ? $columns_foreign->get($column_describe->column_name)->constraint_name : '';

                $constraint_name = collect($constraint_names)->filter()->implode(',');

                $index_name = $columns_index->has($column_describe->column_name) ? $columns_index->get($column_describe->column_name)->index_name : '';

                $referenced = '';
                if ($is_foreign) {
                    $foreign = $columns_foreign->get($column_describe->column_name);

                    $referenced = implode('.', [$foreign->referenced_table_name, $foreign->referenced_column_name]);

                    $referenced .= ' on update '.$foreign->update_rule;
                    $referenced .= ' on delete '.$foreign->delete_rule;
                }

                $schmea_struct[$table_name][] = [
                    'name'            => $column_describe->column_name,
                    'type'            => $column_describe->column_type,
                    'key'             => $column_describe->column_key,
                    'nullable'        => $column_describe->is_nullable,
                    'default'         => $column_describe->column_default,
                    'constraint_name' => $constraint_name,
                    'index_name'      => $index_name,
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
                'information_schema.columns.column_name as column_name',
                'information_schema.columns.column_type as column_type',
                'information_schema.columns.column_key as column_key',
                'information_schema.columns.is_nullable as is_nullable',
                'information_schema.columns.column_default as column_default',
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
                'information_schema.key_column_usage.column_name as column_name',
                'information_schema.key_column_usage.constraint_name as constraint_name',
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
            ->join('information_schema.referential_constraints', 'information_schema.key_column_usage.constraint_name', '=', 'information_schema.referential_constraints.constraint_name')
            ->where('information_schema.key_column_usage.table_schema', $database_name)
            ->where('information_schema.key_column_usage.table_name', $table_name)
            ->select([
                'information_schema.key_column_usage.column_name as column_name',
                'information_schema.key_column_usage.referenced_table_name as referenced_table_name',
                'information_schema.key_column_usage.referenced_column_name as referenced_column_name',
                'information_schema.key_column_usage.constraint_name as constraint_name',
                'information_schema.referential_constraints.update_rule as update_rule',
                'information_schema.referential_constraints.delete_rule as delete_rule',
            ])
            ->get()
            ->keyBy('column_name');
    }

    protected function getColumnIndex(string $database_name, string $table_name)
    {
        return $this->connection
            ->table('information_schema.statistics')
            ->where('table_schema', $database_name)
            ->where('table_name', $table_name)
            ->select([
                'information_schema.statistics.column_name as column_name',
                'information_schema.statistics.index_name as index_name',
            ])
            ->get()
            ->keyBy('column_name');
    }
}
