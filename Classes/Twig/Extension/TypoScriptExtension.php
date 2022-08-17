<?php

/*
 * Twig extension for TYPO3 CMS
 * Copyright (C) 2022 CARL von CHIARI GmbH
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
use Twig\TwigFunction;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * @internal
 */
final class TypoScriptExtension extends AbstractExtension
{
    protected array $typoScriptSetup;
    protected ContentObjectRenderer $contentObjectRenderer;

    public function __construct(ConfigurationManagerInterface $configurationManager, ContentObjectRenderer $contentObjectRenderer)
    {
        $this->typoScriptSetup = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $this->contentObjectRenderer = $contentObjectRenderer;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('t3_cobject', [$this, 'renderCObject'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Renders a TypoScript object. The content object renderer can be populated using the data argument.
     *
     * @param mixed|null $data
     */
    public function renderCObject(string $typoScriptObjectPath, $data = null, string $currentValueKey = null, string $table = null)
    {
        /*
         * Sets the $TSFE->cObjectDepthCounter in Backend mode
         * This somewhat hacky work around is currently needed because the cObjGetSingle() function of
         * \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer relies on this setting.
         */
        if (ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isBackend()) {
            $tsfeBackup = isset($GLOBALS['TSFE']) ? $GLOBALS['TSFE'] : null;
            $GLOBALS['TSFE'] = new \stdClass();
        }
        $currentValue = null;
        if (is_object($data)) {
            $data = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getGettableProperties($data);
        } elseif (is_string($data) || is_numeric($data)) {
            $currentValue = (string) $data;
            $data = [$data];
        }
        $this->contentObjectRenderer->start($data, $table);
        if ($currentValue !== null) {
            $this->contentObjectRenderer->setCurrentVal($currentValue);
        } elseif ($currentValueKey !== null && isset($data[$currentValueKey])) {
            $this->contentObjectRenderer->setCurrentVal($data[$currentValueKey]);
        }
        $pathSegments = GeneralUtility::trimExplode('.', $typoScriptObjectPath);
        $lastSegment = array_pop($pathSegments);
        $setup = $this->typoScriptSetup;
        foreach ($pathSegments as $segment) {
            if (!array_key_exists($segment.'.', $setup)) {
                throw new \RuntimeException('TypoScript object path "'.htmlspecialchars($typoScriptObjectPath).'" does not exist', 1253191023);
            }
            $setup = $setup[$segment.'.'];
        }
        $content = $this->contentObjectRenderer->cObjGetSingle($setup[$lastSegment], $setup[$lastSegment.'.']);
        if (isset($tsfeBackup)) {
            $GLOBALS['TSFE'] = $tsfeBackup;
        }

        return $content;
    }
}
