<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = new Finder()
    ->in(__DIR__)
    ->exclude([
        'vendor',
    ]);

return new Config()
    ->setRiskyAllowed(true)
    ->setRules([
        '@auto' => true,
        '@auto:risky' => true,
        '@PhpCsFixer:risky' => true,
        'php_unit_test_case_static_method_calls' => [
            'call_type' => 'this',
        ],
    ])
    ->setFinder($finder)
    ->setCacheFile('/var/cache/php-cs-fixer/.php-cs-fixer.cache')
;