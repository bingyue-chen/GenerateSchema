<?php

namespace Snowcookie\GenerateSchema\Contracts;

interface GeneratorRenderer
{
    public function render(string $driver_name, string $database_anme, array $schmea_struct): bool;
}
