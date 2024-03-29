<?php

namespace Snowcookie\GenerateSchema\Renderers;

use Illuminate\Support\Facades\Storage;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;
use Snowcookie\GenerateSchema\Contracts\GeneratorRenderer;

class XlsxRenderer implements GeneratorRenderer
{
    public function render(string $disk_name, string $database_name, array $schmea_struct): bool
    {
        $storage = Storage::disk($disk_name);

        $xlsx_writer = new Writer();

        $file_path     = $database_name.'.xlsx';
        $tmp_file_path = '/tmp/'.$file_path;

        $xlsx_writer->openToFile($tmp_file_path);

        $first_sheet = true;

        foreach ($schmea_struct as $table_name => $table_column_schema) {
            if (!$first_sheet) {
                $xlsx_writer->addNewSheetAndMakeItCurrent();
            } else {
                $first_sheet = false;
            }

            $xlsx_writer->getCurrentSheet()->setName($table_name);

            $rows = [];

            $rows[] = Row::fromValues(array_keys($table_column_schema[0]));

            foreach ($table_column_schema as $column_schema) {
                $rows[] = Row::fromValues($column_schema);
            }

            $xlsx_writer->addRows($rows);
        }

        $xlsx_writer->close();

        $file_content = file_get_contents($tmp_file_path);

        $storage->put($file_path, $file_content);

        return true;
    }
}
