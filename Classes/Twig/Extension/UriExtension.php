<?php

/*
 * Twig extension for TYPO3 CMS
 * Copyright (C) 2021 CARL von CHIARI GmbH
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

use Cvc\Typo3\CvcTwig\Extbase\Mvc\ControllerContextStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Service\TypoLinkCodecService;

/**
 * Provides functions to generate URIs.
 *
 * @internal
 */
final class UriExtension extends AbstractExtension
{
    private ControllerContextStack $controllerContextStack;
    private TypoLinkCodecService $typoLinkCodecService;
    private DataMapper $dataMapper;

    public function __construct(
        ControllerContextStack $controllerContextStack,
        TypoLinkCodecService $typoLinkCodecService,
        ObjectManager $objectManager
    ) {
        $this->controllerContextStack = $controllerContextStack;
        $this->typoLinkCodecService = $typoLinkCodecService;
        $this->dataMapper = $objectManager->get(DataMapper::class);
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('t3_uri_action', [$this, 'uriAction']),
            new TwigFunction('t3_uri_model', [$this, 'modelUri']),
            new TwigFunction('t3_uri_page', [$this, 'uriPage']),
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
        $uriBuilder = $this->controllerContextStack->getControllerContext()->getUriBuilder()->reset();

        if ($pageUid !== null) {
            $uriBuilder->setTargetPageUid($pageUid);
        }

        return $uriBuilder
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
        $uriBuilder = $this->controllerContextStack->getControllerContext()->getUriBuilder()->reset();

        if ($pageUid !== null) {
            $uriBuilder->setTargetPageUid($pageUid);
        }

        return $uriBuilder
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

    /**
     * Generates a link for the given record.
     *
     * A `link handler <https://docs.typo3.org/typo3cms/extensions/core/latest/Changelog/8.6/Feature-79626-IntegrateRecordLinkHandler.html>`__ must be configured for the mapped table.
     *
     * @param string $table     the table of the record
     * @param int    $recordUid the UID of the record
     */
    public function recordUri(string $table, int $recordUid): ?string
    {
        $paramter = 't3://record?'.http_build_query(['identifier' => $table, 'uid' => $recordUid]);

        return static::typoLinkUri($paramter);
    }

    /**
     * Generates a link fro the given domain model.
     *
     * A `link handler <https://docs.typo3.org/typo3cms/extensions/core/latest/Changelog/8.6/Feature-79626-IntegrateRecordLinkHandler.html>`__ must be configured for the mapped table.
     */
    public function modelUri(DomainObjectInterface $model): ?string
    {
        $table = $this->dataMapper->convertClassNameToTableName(get_class($model));
        $recordUid = $model->getUid();

        return static::recordUri($table, $recordUid);
    }

    public function typoLinkUri(
        string $parameter,
        array $additionalParams = []
    ): ?string {
        $content = '';

        if ($parameter) {
            $contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
            $content = $contentObject->typoLink_URL(
                [
                    'parameter' => self::createTypoLinkParameterFromArguments($parameter, http_build_query($additionalParams)),
                ]
            );
        }

        return $content;
    }

    private function createTypoLinkParameterFromArguments(string $parameter, string $additionalParameters): string
    {
        $typoLinkConfiguration = $this->typoLinkCodecService->decode($parameter);
        $typoLinkConfiguration['additionalParams'] .= $additionalParameters;

        return $this->typoLinkCodecService->encode($typoLinkConfiguration);
    }
}
