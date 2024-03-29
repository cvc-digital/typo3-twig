<?php

$year = date('Y');

$headerComment = <<<DOC
Twig extension for TYPO3 CMS
Copyright (C) {$year} CARL von CHIARI GmbH

This file is part of the TYPO3 CMS project.

It is free software; you can redistribute it and/or modify it under
the terms of the GNU General Public License, either version 3
of the License, or any later version.

For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.

The TYPO3 project - inspiring people to share!
DOC;

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PSR2' => true,
    '@Symfony' => true,
    'array_syntax' => ['syntax' => 'short'],
    'combine_consecutive_unsets' => true,
    'heredoc_to_nowdoc' => true,
    'linebreak_after_opening_tag' => true,
    'mb_str_functions' => true,
    'no_php4_constructor' => true,
    'no_unreachable_default_argument_value' => true,
    'no_useless_else' => true,
    'no_useless_return' => true,
    'ordered_class_elements' => true,
    'ordered_imports' => true,
    'php_unit_strict' => true,
    'phpdoc_add_missing_param_annotation' => true,
    'phpdoc_order' => true,
    'semicolon_after_instruction' => true,
    'simplified_null_return' => true,
    'header_comment' => [
        'header' => $headerComment,
    ],
    'yoda_style' => false,
])
    ->setRiskyAllowed(true)
    ->setFinder(PhpCsFixer\Finder::create()->in('.'))
    ->setUsingCache(true);
