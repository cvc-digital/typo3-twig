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
use Twig\TwigFunction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Service\TypoLinkCodecService;

/**
 * Provides functions to generate URIs.
 *
 * @internal
 */
class UriExtension extends AbstractExtension
{
    /**
     * @var UriBuilder
     */
    private $uriBuilder;

    public function __construct(UriBuilder $uriBuilder)
    {
        $this->uriBuilder = $uriBuilder;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('t3_uri_page', [$this, 'uriPage'], ['needs_environment' => true]),
            new TwigFunction('t3_uri_record', [$this, 'recordUri']),
            new TwigFunction('t3_uri_typolink', [$this, 'typoLinkUri']),
        ];
    }

    public function uriPage(
        int $pageUid = null,
        array $additionalParams = [],
        int $pageType = 0,
        bool $noCache = false,
        string $section = '',
        bool $linkAccessRestrictedPages = false,
        bool $absolute = false,
        bool $addQueryString = false,
        array $argumentsToBeExcludedFromQueryString = [],
        string $addQueryStringMethod = ''): ?string
    {
        return $this->uriBuilder
            ->reset()
            ->setTargetPageUid($pageUid)
            ->setTargetPageType($pageType)
            ->setNoCache($noCache)
            ->setSection($section)
            ->setLinkAccessRestrictedPages($linkAccessRestrictedPages)
            ->setArguments($additionalParams)
            ->setCreateAbsoluteUri($absolute)
            ->setAddQueryString($addQueryString)
            ->setArgumentsToBeExcludedFromQueryString($argumentsToBeExcludedFromQueryString)
            ->setAddQueryStringMethod($addQueryStringMethod)
            ->build();
    }

    public function uriAction(
        string $action,
        array $arguments = [],
        string $controller = null,
        string $extensionName = null,
        string $pluginName = null,
        int $pageUid = null,
        int $pageType = 0,
        bool $noCache = false,
        string $section = '',
        string $format = '',
        bool $linkAccessRestrictedPages = false,
        array $additionalParams = [],
        bool $absolute = false,
        bool $addQueryString = false,
        array $argumentsToBeExcludedFromQueryString = [],
        string $addQueryStringMethod = ''): ?string
    {
        return $this->uriBuilder
            ->reset()
            ->setTargetPageUid($pageUid)
            ->setTargetPageType($pageType)
            ->setNoCache($noCache)
            ->setSection($section)
            ->setFormat($format)
            ->setLinkAccessRestrictedPages($linkAccessRestrictedPages)
            ->setArguments($additionalParams)
            ->setCreateAbsoluteUri($absolute)
            ->setAddQueryString($addQueryString)
            ->setArgumentsToBeExcludedFromQueryString($argumentsToBeExcludedFromQueryString)
            ->setAddQueryStringMethod($addQueryStringMethod)
            ->uriFor($action, $arguments, $controller, $extensionName, $pluginName);
    }

    public function recordUri(string $table, int $recordUid): ?string
    {
        $parameter = 't3://record?identifier='.$table.'&uid='.$recordUid;

        return static::typolinkUri($parameter);
    }

    public function typolinkUri(
        string $parameter,
        array $additionalParams = []
    ): ?string {
        $content = '';

        if ($parameter) {
            $contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
            $content = $contentObject->typoLink_URL(
                [
                    'parameter' => self::createTypolinkParameterFromArguments($parameter, $additionalParams),
                ]
            );
        }

        return $content;
    }

    private function createTypolinkParameterFromArguments($parameter, $additionalParameters = '')
    {
        $typoLinkCodec = GeneralUtility::makeInstance(TypoLinkCodecService::class);
        $typolinkConfiguration = $typoLinkCodec->decode($parameter);

        // Combine additionalParams
        if ($additionalParameters) {
            $typolinkConfiguration['additionalParams'] .= $additionalParameters;
        }

        return $typoLinkCodec->encode($typolinkConfiguration);
    }
}
