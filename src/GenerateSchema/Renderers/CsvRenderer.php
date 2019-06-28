<?php

namespace Snowcookie\GenerateSchema\Renderers;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Illuminate\Support\Facades\Storage;
use Snowcookie\GenerateSchema\Contracts\GeneratorRenderer;

class CsvRenderer implements GeneratorRenderer
{
    public function render(string $disk_name, string $database_name, array $schmea_struct): bool
    {
        $storage = Storage::disk($disk_name);

        $csv_writer = WriterEntityFactory::createCSVWriter();

        $temp_dir = '/tmp';

        foreach ($schmea_struct as $table_name => $table_column_schema) {
            if (is_array($table_column_schema) && !empty($table_column_schema)) {
                $file_path = $temp_dir.'/'.$table_name.'.csv';

                $rows = [];

                $rows[] = WriterEntityFactory::createRowFromArray(array_keys($table_column_schema[0]));

                foreach ($table_column_schema as $column_schema) {
                    $rows[] = WriterEntityFactory::createRowFromArray($column_schema);
                }

                $csv_writer->openToFile($file_path)->addRows($rows)->close();

                $file_content = file_get_contents($file_path);
            } else {
                $file_content = '';
            }

            $storage->put($table_name.'.csv', $file_content);
        }

        return true;
    }
}
