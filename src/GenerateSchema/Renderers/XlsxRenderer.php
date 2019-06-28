<?php

namespace Snowcookie\GenerateSchema\Renderers;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Illuminate\Support\Facades\Storage;
use Snowcookie\GenerateSchema\Contracts\GeneratorRenderer;

class XlsxRenderer implements GeneratorRenderer
{
    public function render(string $disk_name, string $database_name, array $schmea_struct): bool
    {
        $storage = Storage::disk($disk_name);

        $xlsx_writer = WriterEntityFactory::createXLSXWriter();

        $file_path = '/tmp/'.$database_name.'.xlsx';

        $xlsx_writer->openToFile($file_path);

        $first_sheet = true;

        foreach ($schmea_struct as $table_name => $table_column_schema) {
            if (\is_array($table_column_schema) && !empty($table_column_schema)) {
                if (!$first_sheet) {
                    $xlsx_writer->addNewSheetAndMakeItCurrent();
                } else {
                    $first_sheet = false;
                }

                $xlsx_writer->getCurrentSheet()->setName($table_name);

                $rows = [];

                $rows[] = WriterEntityFactory::createRowFromArray(array_keys($table_column_schema[0]));

                foreach ($table_column_schema as $column_schema) {
                    $rows[] = WriterEntityFactory::createRowFromArray($column_schema);
                }

                $xlsx_writer->addRows($rows);
            }
        }

        $xlsx_writer->close();

        $file_content = file_get_contents($file_path);

        $storage->put($database_name.'.xlsx', $file_content);

        return true;
    }
}
