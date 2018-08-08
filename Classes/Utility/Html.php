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

namespace Carl\Typo3\Twig\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

final class Html
{
    /**
     * @var TypoScriptFrontendController contains a backup of the current['TSFE'] if used in BE mode
     */
    private static $tsfeBackup;

    private function __construct()
    {
        // static util class
        // instantiation not needed
    }

    /**
     * Formats HTML that was generated with a rich text editor.
     *
     * @param string $html
     * @param string $parseFuncTSPath
     *
     * @return string
     */
    public static function format(string $html, $parseFuncTSPath = 'lib.parseFunc_RTE'): string
    {
        if (TYPO3_MODE === 'BE') {
            self::simulateFrontendEnvironment();
        }

        $contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $contentObject->start([]);
        $formattedHtml = $contentObject->parseFunc($html, [], '< '.$parseFuncTSPath);

        if (TYPO3_MODE === 'BE') {
            self::resetFrontendEnvironment();
        }

        return $formattedHtml;
    }

    /**
     * Copies the specified parseFunc configuration to $GLOBALS['TSFE']->tmpl->setup in Backend mode
     * This somewhat hacky work around is currently needed because the parseFunc() function of \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer relies on those variables to be set.
     */
    private static function simulateFrontendEnvironment()
    {
        self::$tsfeBackup = isset($GLOBALS['TSFE']) ? $GLOBALS['TSFE'] : null;
        $GLOBALS['TSFE'] = new \stdClass();
        $GLOBALS['TSFE']->tmpl = new \stdClass();
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $configurationManager = $objectManager->get(ConfigurationManagerInterface::class);
        $GLOBALS['TSFE']->tmpl->setup = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
    }

    /**
     * Resets $GLOBALS['TSFE'] if it was previously changed by simulateFrontendEnvironment().
     *
     * @see simulateFrontendEnvironment()
     */
    private static function resetFrontendEnvironment()
    {
        $GLOBALS['TSFE'] = self::$tsfeBackup;
    }
}
