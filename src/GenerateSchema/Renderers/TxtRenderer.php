<?php

namespace Snowcookie\GenerateSchema\Renderers;

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

        foreach ($schmea_struct as $table_name => $table_column_schema) {
            if (is_array($table_column_schema) && !empty($table_column_schema)) {
                $headers = array_keys($table_column_schema[0]);

                $table->setHeaders($headers)->setRows($table_column_schema)->render();

                $table_schema_txt_style = $buffer->fetch();
            } else {
                $table_schema_txt_style = '';
            }

            $storage->put($table_name.'.txt', $table_schema_txt_style);
        }

        return true;
    }
}
