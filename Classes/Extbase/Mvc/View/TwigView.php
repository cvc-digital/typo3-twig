<?php

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

namespace Cvc\Typo3\CvcTwig\Extbase\Mvc\View;

use Cvc\Typo3\CvcTwig\Mvc\View\StandaloneView;
use Cvc\Typo3\CvcTwig\Mvc\View\StandaloneViewFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class TwigView extends \TYPO3\CMS\Fluid\View\StandaloneView
{
    private StandaloneView $standaloneView;

    public function __construct()
    {
        parent::__construct();
        $this->standaloneView = GeneralUtility::makeInstance(StandaloneViewFactory::class)->create();
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

    public function render($actionName = null)
    {
        $renderingContext = $this->getCurrentRenderingContext();
        if ($renderingContext === null) {
            return '';
        }

        $request = $renderingContext->getRequest();
        $action = $renderingContext->getControllerAction();
        $controller = $renderingContext->getControllerName();
        $format = $request->getFormat();

        $this->standaloneView->setTemplateName("$controller/$action.$format.twig");

        return $this->standaloneView->render();
    }

    public function setTemplateRootPaths(array $templateRootPaths): void
    {
        $this->standaloneView->setTemplateRootPaths($templateRootPaths);
    }
}
