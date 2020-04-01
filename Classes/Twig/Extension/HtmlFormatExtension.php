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

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * @internal
 */
final class HtmlFormatExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('t3_html', [static::class, 'format'], [
                'is_safe' => ['html'],
            ]),
        ];
    }

    /**
     * Parses HTML that was created with an rich text editor.
     *
     * @param string $html            The HTML that should be processed. Normally this is the content that is stored in the database.
     * @param string $parseFuncTSPath here you can define which setup should be used to transform the HTML content
     */
    public static function format(string $html, string $parseFuncTSPath = 'lib.parseFunc_RTE'): string
    {
        if (TYPO3_MODE === 'BE') {
            /*
             * Copies the specified parseFunc configuration to $GLOBALS['TSFE']->tmpl->setup in Backend mode
             * This somewhat hacky work around is currently needed because the parseFunc() function of
             * \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer relies on those variables to be set.
             */
            $tsfeBackup = isset($GLOBALS['TSFE']) ? $GLOBALS['TSFE'] : null;
            $GLOBALS['TSFE'] = new \stdClass();
            $GLOBALS['TSFE']->tmpl = new \stdClass();
            $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
            $configurationManager = $objectManager->get(ConfigurationManagerInterface::class);
            $GLOBALS['TSFE']->tmpl->setup = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        }

        $contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $contentObject->start([]);
        $formattedHtml = $contentObject->parseFunc($html, [], '< '.$parseFuncTSPath);

        if (isset($tsfeBackup)) {
            $GLOBALS['TSFE'] = $tsfeBackup;
        }

        return $formattedHtml;
    }
}
