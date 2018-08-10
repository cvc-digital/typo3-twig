<?php

/*
 * Twig Extension for TYPO3 CMS
 * Copyright (C) 2018 Carl von Chiari GmbH
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

namespace Carl\Typo3\Twig\Twig\Extension;

use Carl\Typo3\Twig\Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * @final
 */
class TranslationExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('t3_trans', [static::class, 'translate'], ['needs_environment' => true]),
        ];
    }

    public static function translate(
        Environment $environment,
        string $key,
        array $arguments = [],
        string $extensionName = null
    ): ?string {
        if ($extensionName === null) {
            $extensionName = $environment->getControllerContext()->getRequest()->getControllerExtensionName();
        }

        return LocalizationUtility::translate($key, $extensionName, $arguments);
    }
}
