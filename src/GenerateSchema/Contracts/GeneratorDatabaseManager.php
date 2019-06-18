<?php

namespace Snowcookie\GenerateSchema\Contracts;

interface GeneratorDatabaseManager
{
    public function getConnectionName(): string;

    public function getAllTableName(string $database_name): array;

    public function getEachTableColumnType(string $database_name, array $database_tables): array;
}
