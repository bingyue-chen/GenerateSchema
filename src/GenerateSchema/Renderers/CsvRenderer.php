<?php

namespace Snowcookie\GenerateSchema\Renderers;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Exception;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Snowcookie\GenerateSchema\Contracts\GeneratorRenderer;

class CsvRenderer implements GeneratorRenderer
{
    public function render(string $disk_name, string $database_name, array $schmea_struct): bool
    {
        $storage = Storage::disk($disk_name);

        $csv_writer = WriterEntityFactory::createCSVWriter();

        $files_path = [];

        try {
            foreach ($schmea_struct as $table_name => $table_column_schema) {
                $table_file_path     = $table_name.'.csv';
                $tmp_table_file_path = '/tmp/'.$table_file_path;

                $rows = [];

                $rows[] = WriterEntityFactory::createRowFromArray(array_keys($table_column_schema[0]));

                foreach ($table_column_schema as $column_schema) {
                    $rows[] = WriterEntityFactory::createRowFromArray($column_schema);
                }

                $csv_writer->openToFile($tmp_table_file_path)->addRows($rows)->close();

                $file_content = file_get_contents($tmp_table_file_path);

                $storage->put($table_file_path, $file_content);

                $files_path[] = $table_file_path;
            }
        } catch (Exception $e) {
            $this->cleanFiles($storage, $files_path);
            throw $e;
        }

        return true;
    }

    private function cleanFiles(FilesystemAdapter $storage, array $files_path)
    {
        foreach ($files_path as $path) {
            $storage->delete($path);
        }
    }
}
