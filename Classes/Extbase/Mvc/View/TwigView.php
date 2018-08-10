<?php

/*
 * Twig extension for TYPO3 CMS
 * Copyright (C) 2018 CARL von CHIARI GmbH
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

namespace Cvc\Typo3\Twig\Extbase\Mvc\View;

use Cvc\Typo3\Twig\Mvc\View\StandaloneView;
use TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

class TwigView extends StandaloneView implements ViewInterface
{
    public function setControllerContext(ControllerContext $controllerContext)
    {
        $this->controllerContext = $controllerContext;
    }

    public function canRender(ControllerContext $controllerContext)
    {
        return true;
    }

    public function initializeView()
    {
        if ($this->controllerContext !== null) {
            $request = $this->controllerContext->getRequest();

            $action = $request->getControllerActionName();
            $controller = $request->getControllerName();
            $format = $request->getFormat();

            $this->setTemplateName("$controller/$action.$format.twig");
        }
    }
}
