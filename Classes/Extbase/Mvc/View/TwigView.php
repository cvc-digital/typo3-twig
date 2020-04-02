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

namespace Cvc\Typo3\CvcTwig\Extbase\Mvc\View;

use Cvc\Typo3\CvcTwig\Extbase\Mvc\ControllerContextStack;
use Cvc\Typo3\CvcTwig\Mvc\View\StandaloneView;
use Cvc\Typo3\CvcTwig\Mvc\View\StandaloneViewFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

final class TwigView implements ViewInterface
{
    private StandaloneView $standaloneView;
    private ?ControllerContext $controllerContext = null;
    private ControllerContextStack $controllerContextStack;

    public function __construct(ControllerContextStack $controllerContextStack)
    {
        $this->standaloneView = GeneralUtility::makeInstance(StandaloneViewFactory::class)->create();
        $this->controllerContextStack = $controllerContextStack;
    }

    public function setControllerContext(ControllerContext $controllerContext)
    {
        $this->controllerContext = $controllerContext;
    }

    public function assign($key, $value)
    {
        $this->standaloneView->assign($key, $value);

        return $this;
    }

    public function assignMultiple(array $values)
    {
        $this->standaloneView->assignMultiple($values);

        return $this;
    }

    public function canRender(ControllerContext $controllerContext)
    {
        return true;
    }

    public function render()
    {
        $hasControllerContext = $this->controllerContext !== null;

        if ($hasControllerContext) {
            $this->controllerContextStack->push($this->controllerContext);
        }

        $content = $this->standaloneView->render();

        if ($hasControllerContext) {
            $this->controllerContextStack->pop();
        }

        return $content;
    }

    public function initializeView()
    {
        if ($this->controllerContext === null) {
            return;
        }

        $request = $this->controllerContext->getRequest();

        $action = $request->getControllerActionName();
        $controller = $request->getControllerName();
        $format = $request->getFormat();

        $this->standaloneView->setTemplateName("$controller/$action.$format.twig");
    }
}
