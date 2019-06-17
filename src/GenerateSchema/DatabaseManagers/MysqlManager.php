<?php

namespace Snowcookie\GenerateSchema\DatabaseManagers;

use Illuminate\Support\Facades\DB;
use Snowcookie\GenerateSchema\Contracts\GeneratorDatabaseManager;

class MysqlManager implements GeneratorDatabaseManager
{
    protected $connection = 'mysql';

    public function getConnection(): string
    {
        return $this->connection;
    }

    public function getAllTableName(string $database_name): array
    {
        return DB::table('information_schema.tables')->select(['table_name'])->where('table_schema', $database_name)->get()->pluck('TABLE_NAME')->all();
    }

    public function getEachTableColumnType(string $database_name, array $database_tables): array
    {
        $schmea_struct = [];

        foreach ($database_tables as $table_name) {
            $schmea_struct[$table_name] = [];

            $columns_describe = DB::select('describe '.$table_name);

            $columns_unique = DB::table('information_schema.key_column_usage')->where('table_name', $table_name)->where('table_schema', $database_name)->where('constraint_name', 'like', '%unique%')->select(['column_name', 'constraint_name'])->get()->keyBy('column_name');

            $columns_foreign = DB::table('information_schema.key_column_usage')->where('table_name', $table_name)->where('table_schema', $database_name)->where('constraint_name', 'like', '%foreign%')->select(['column_name', 'referenced_table_name', 'referenced_column_name', 'constraint_name'])->get()->keyBy('column_name');

            foreach ($columns_describe as $column_describe) {
                $is_unique  = $columns_unique->has($column_describe->Field);
                $is_foreign = $columns_foreign->has($column_describe->Field);

                $constraint_names   = [];
                $constraint_names[] = $is_unique ? $columns_unique->get($column_describe->Field)->constraint_name : '';
                $constraint_names[] = $is_foreign ? $columns_foreign->get($column_describe->Field)->constraint_name : '';

                $constraint_name = collect($constraint_names)->filter()->implode(',');

                $referenced = $is_foreign ? implode('.', [$columns_foreign->get($column_describe->Field)->referenced_table_name, $columns_foreign->get($column_describe->Field)->referenced_column_name]) : '';

                $schmea_struct[$table_name][] = [
                    'name'            => $column_describe->Field,
                    'type'            => $column_describe->Type,
                    'key'             => $column_describe->Key,
                    'nullable'        => $column_describe->{'Null'},
                    'default'         => $column_describe->Default,
                    'constraint_name' => $constraint_name,
                    'referenced'      => $referenced,
                ];
            }
        }

        return $schmea_struct;
    }
}
