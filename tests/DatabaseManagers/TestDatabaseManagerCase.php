<?php

namespace Snowcookie\GenerateSchema\Test\DatabaseManagers;

use Snowcookie\GenerateSchema\Test\TestCase;

class TestDatabaseManagerCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->refreshDatabase();
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
