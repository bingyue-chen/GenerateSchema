<?php

namespace Snowcookie\GenerateSchema\Contracts;

interface GeneratorRenderer
{
    public function render(string $disk_nake, string $database_anme, array $schmea_struct): bool;
}
