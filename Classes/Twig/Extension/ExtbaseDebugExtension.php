<?php

/*
 * Twig extension for TYPO3 CMS
 * Copyright (C) 2020 CARL von CHIARI GmbH
 *
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 3
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Cvc\Typo3\CvcTwig\Twig\Extension;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Template;
use Twig\TwigFunction;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * @internal
 */
class ExtbaseDebugExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'dump',
                [static::class, 'dump'],
                [
                    'is_safe' => ['html'],
                    'needs_context' => true,
                    'needs_environment' => true,
                ]
            ),
        ];
    }

    /**
     * Displays the content of the variable(s) on the screen.
     *
     * The :code:`dump()` functions prints the contents of a variable.
     * This is useful for debugging.
     * Please ensure that the frontend debug mode is on, because otherwise the function does not print anything.
     * Internally :code:`DebuggerUtility::var_dump()` is used.
     *
     * @param mixed ...$vars Any number of variables.
     * @param mixed $context
     *
     * @return false|string|void
     *
     * @example {# print a single variable #}
     *          {{ dump(foo) }}
     *
     *          {# print multiple variables #}
     *          {{ dump(foo, bar, baz) }}
     *
     *          {# print all variables #}
     *          {{ dump() }}
     */
    public static function dump(Environment $env, $context, ...$vars)
    {
        if (!$env->isDebug()) {
            return;
        }

        ob_start();

        if (!$vars) {
            $vars = [];
            foreach ($context as $key => $value) {
                if (!$value instanceof Template) {
                    $vars[$key] = $value;
                }
            }
            DebuggerUtility::var_dump($vars, 'Twig dump()');
        } else {
            foreach ($vars as $var) {
                DebuggerUtility::var_dump($var);
            }
        }

        return ob_get_clean();
    }
}
