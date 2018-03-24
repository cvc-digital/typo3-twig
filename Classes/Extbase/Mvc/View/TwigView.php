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

namespace Carl\Typo3\Twig\Extbase\Mvc\View;

use Carl\Typo3\Twig\Mvc\View\StandaloneView;
use TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

class TwigView extends StandaloneView implements ViewInterface
{
    public function setControllerContext(ControllerContext $controllerContext)
    {
        // left empty, because the controller context is not used yet
    }

    public function canRender(ControllerContext $controllerContext)
    {
        return true;
    }

    public function initializeView()
    {
        // left empty, because there is nothing to initialize
    }
}
