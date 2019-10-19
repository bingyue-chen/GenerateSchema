<?php

namespace Snowcookie\GenerateSchema\Renderers;

use Exception;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Snowcookie\GenerateSchema\Contracts\GeneratorRenderer;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\BufferedOutput;

class TxtRenderer implements GeneratorRenderer
{
    public function render(string $disk_name, string $database_name, array $schmea_struct): bool
    {
        $storage = Storage::disk($disk_name);
        $buffer  = new BufferedOutput();
        $table   = new Table($buffer);

        $files_path = [];

        try {
            foreach ($schmea_struct as $table_name => $table_column_schema) {
                $table_file_path = $table_name.'.txt';

                $headers = array_keys($table_column_schema[0]);

                $table->setHeaders($headers)->setRows($table_column_schema)->render();

                $table_schema_txt_style = $buffer->fetch();

                $storage->put($table_file_path, $table_schema_txt_style);

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
