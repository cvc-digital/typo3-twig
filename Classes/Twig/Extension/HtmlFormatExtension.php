<?php

declare(strict_types=1);

/*
 * Twig extension for TYPO3 CMS
 * Copyright (C) 2024 CARL von CHIARI GmbH
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
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * @internal
 */
final class HtmlFormatExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('t3_html', [$this, 'format'], [
                'is_safe' => ['html'],
            ]),
        ];
    }

    /**
     * Parses HTML that was created with a rich text editor.
     *
     * @param string $html            The HTML that should be processed. Normally this is the content that is stored in the database.
     * @param string $parseFuncTSPath here you can define which setup should be used to transform the HTML content
     */
    public static function format(string $html, string $parseFuncTSPath = 'lib.parseFunc_RTE'): string
    {
        $contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $contentObject->start([]);
        return $contentObject->parseFunc($html, [], '< '.$parseFuncTSPath);
    }
}
