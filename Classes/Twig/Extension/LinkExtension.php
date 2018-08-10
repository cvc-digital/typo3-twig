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
use Twig\TwigFunction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Service\TypoLinkCodecService;

/**
 * Provides functions to generate URIs.
 *
 * @internal
 */
class LinkExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('t3_uri_page', [static::class, 'uriPage'], ['needs_environment' => true]),
            new TwigFunction('t3_uri_action', [static::class, 'uriAction'], ['needs_environment' => true]),
            new TwigFunction('t3_uri_model', [static::class, 'modelUri']),
            new TwigFunction('t3_uri_record', [static::class, 'recordUri']),
            new TwigFunction('t3_uri_typolink', [static::class, 'typoLinkUri']),
        ];
    }

    public static function uriPage(
        Environment $environment,
        int $pageUid = null,
        array $additionalParams = [],
        int $pageType = 0,
        bool $noCache = false,
        bool $noCacheHash = false,
        string $section = '',
        bool $linkAccessRestrictedPages = false,
        bool $absolute = false,
        bool $addQueryString = false,
        array $argumentsToBeExcludedFromQueryString = [],
        string $addQueryStringMethod = ''): ?string
    {
        return $environment->getControllerContext()->getUriBuilder()
            ->setTargetPageUid($pageUid)
            ->setTargetPageType($pageType)
            ->setNoCache($noCache)
            ->setUseCacheHash(!$noCacheHash)
            ->setSection($section)
            ->setLinkAccessRestrictedPages($linkAccessRestrictedPages)
            ->setArguments($additionalParams)
            ->setCreateAbsoluteUri($absolute)
            ->setAddQueryString($addQueryString)
            ->setArgumentsToBeExcludedFromQueryString($argumentsToBeExcludedFromQueryString)
            ->setAddQueryStringMethod($addQueryStringMethod)
            ->build();
    }

    public static function uriAction(
        Environment $environment,
        string $action,
        array $arguments = [],
        string $controller = null,
        string $extensionName = null,
        string $pluginName = null,
        int $pageUid = null,
        int $pageType = 0,
        bool $noCache = false,
        bool $noCacheHash = false,
        string $section = '',
        string $format = '',
        bool $linkAccessRestrictedPages = false,
        array $additionalParams = [],
        bool $absolute = false,
        bool $addQueryString = false,
        array $argumentsToBeExcludedFromQueryString = [],
        string $addQueryStringMethod = ''): ?string
    {
        return $environment->getControllerContext()->getUriBuilder()
            ->reset()
            ->setTargetPageUid($pageUid)
            ->setTargetPageType($pageType)
            ->setNoCache($noCache)
            ->setUseCacheHash(!$noCacheHash)
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

    public static function modelUri(DomainObjectInterface $model): ?string
    {
        $dataMapper = GeneralUtility::makeInstance(DataMapper::class);

        $table = $dataMapper->convertClassNameToTableName(get_class($model));
        $recordUid = $model->getUid();

        return static::recordUri($table, $recordUid);
    }

    public static function recordUri(string $table, int $recordUid): ?string
    {
        $parameter = 't3://record?identifier='.$table.'&uid='.$recordUid;

        return static::typolinkUri($parameter);
    }

    public static function typolinkUri(
        string $parameter,
        array $additionalParams = [],
        bool $useCacheHash = false): ?string
    {
        $content = '';

        if ($parameter) {
            $contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
            $content = $contentObject->typoLink_URL(
                [
                    'parameter' => self::createTypolinkParameterFromArguments($parameter, $additionalParams),
                    'useCacheHash' => $useCacheHash,
                ]
            );
        }

        return $content;
    }

    private static function createTypolinkParameterFromArguments($parameter, $additionalParameters = '')
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
