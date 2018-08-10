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

namespace Cvc\Typo3\Twig\Twig;

use Twig\Environment as TwigEnvironment;
use Twig\Loader\LoaderInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext;

class Environment extends TwigEnvironment
{
    /**
     * The controller context is needed for functions and filters that relies on Extbase.
     *
     * @var ControllerContext
     */
    private $controllerContext;

    public function __construct(
        LoaderInterface $loader,
        $options = [],
        ControllerContext $controllerContext = null)
    {
        parent::__construct($loader, $options);
        $this->controllerContext = $controllerContext;
    }

    public function getControllerContext(): ControllerContext
    {
        return $this->controllerContext;
    }
}
