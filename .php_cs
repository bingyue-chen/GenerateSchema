<?php

$finder = PhpCsFixer\Finder::create()
            ->exclude('docker')
            ->exclude('vendor')
            ->exclude('tests/migrations')
            ->in(__DIR__);

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR1' => true,
        '@PSR2' => true,
        '@Symfony' => true,
        'binary_operator_spaces' => ['default' => 'align_single_space_minimal'],
        'ordered_imports' => true,
    ])
    ->setFinder($finder)
    ->setUsingCache(true);
