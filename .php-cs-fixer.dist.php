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
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        'php_unit_test_case_static_method_calls' => [
            'call_type' => 'this',
        ],
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
        ],
        'multiline_promoted_properties' => [
            'minimum_number_of_parameters' => 1,
        ],
        'trailing_comma_in_multiline' => [
            'elements' => ['arguments', 'array_destructuring', 'arrays', 'match', 'parameters'],
        ],
    ])
    ->setFinder($finder)
    ->setCacheFile('/var/cache/php-cs-fixer/.php-cs-fixer.cache')
;