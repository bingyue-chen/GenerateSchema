<?php

$finder = PhpCsFixer\Finder::create()
            ->exclude('docker')
            ->exclude('vendor')
            ->exclude('tests/migrations')
            ->in(__DIR__);

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'binary_operator_spaces' => ['default' => 'align_single_space_minimal'],
    ])
    ->setRiskyAllowed('yes')
    ->setFinder($finder)
    ->setUsingCache(true);
