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

namespace Cvc\Typo3\CvcTwig\Extbase\Mvc;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

final class ControllerContextStack
{
    private array $stack = [];
    private ?ControllerContext $default = null;
    private ObjectManager $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @internal
     */
    public function push(ControllerContext $controllerContext): void
    {
        array_push($this->stack, $controllerContext);
    }

    /**
     * @internal
     */
    public function pop(): ControllerContext
    {
        return array_pop($this->stack);
    }

    public function getControllerContext(): ControllerContext
    {
        return end($this->stack) ?: $this->getDefault();
    }

    public function getDefault(): ControllerContext
    {
        if ($this->default === null) {
            $configurationManager = $this->objectManager->get(ConfigurationManagerInterface::class);
            $contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
            $configurationManager->setContentObject($contentObject);

            /** @var Request $request */
            $request = $this->objectManager->get(Request::class);
            $uriBuilder = $this->objectManager->get(UriBuilder::class);
            $uriBuilder->setRequest($request);
            $this->default = $this->objectManager->get(ControllerContext::class);
            $this->default->setRequest($request);
            $this->default->setUriBuilder($uriBuilder);
        }

        return $this->default;
    }
}
