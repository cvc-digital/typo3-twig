<?php

/*
 * Twig extension for TYPO3 CMS
 * Copyright (C) 2023 CARL von CHIARI GmbH
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

use Cvc\Typo3\CvcTwig\Extbase\Mvc\RenderingContextStack;
use Cvc\Typo3\CvcTwig\Mvc\View\StandaloneView;
use Cvc\Typo3\CvcTwig\Mvc\View\StandaloneViewFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewInterface;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;

final class TwigView implements ViewInterface
{
    private StandaloneView $standaloneView;
    private ?RenderingContext $renderingContext = null;
    private RenderingContextStack $controllerContextStack;

    public function __construct(RenderingContextStack $controllerContextStack)
    {
        $this->standaloneView = GeneralUtility::makeInstance(StandaloneViewFactory::class)->create();
        $this->controllerContextStack = $controllerContextStack;
    }

    public function setRenderingContext(RenderingContext $renderingContext)
    {
        $this->renderingContext = $renderingContext;
    }

    public function assign($key, $value): self
    {
        $this->standaloneView->assign($key, $value);

        return $this;
    }

    public function assignMultiple(array $values): self
    {
        $this->standaloneView->assignMultiple($values);

        return $this;
    }

    public function canRender(RenderingContext $controllerContext): bool
    {
        return true;
    }

    public function render(string $templateFileName = ''): string
    {
        $hasControllerContext = $this->renderingContext !== null;

        if ($hasControllerContext) {
            $this->controllerContextStack->push($this->renderingContext);
        }

        $content = $this->standaloneView->render();

        if ($hasControllerContext) {
            $this->controllerContextStack->pop();
        }

        return $content;
    }

    public function initializeView()
    {
        if ($this->renderingContext === null) {
            return;
        }

        $request = $this->renderingContext->getRequest();

        $action = $this->renderingContext->getControllerAction();
        $controller = $this->renderingContext->getControllerName();
        $format = $request->getFormat();

        $this->standaloneView->setTemplateName("$controller/$action.$format.twig");
    }

    public function setTemplateRootPaths(array $templateRootPaths)
    {
        $this->standaloneView->setTemplateRootPaths($templateRootPaths);
    }
}
