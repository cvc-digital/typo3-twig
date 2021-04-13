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

use Cvc\Typo3\CvcTwig\DependencyInjection\TwigEnvironmentCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Twig\Extension\ExtensionInterface;
use TYPO3\CMS\Core\Core\Environment;

return function (ContainerConfigurator $container, ContainerBuilder $containerBuilder) {
    $containerBuilder->registerForAutoconfiguration(ExtensionInterface::class)->addTag('twig.extension');
    $containerBuilder->addCompilerPass(
        new TwigEnvironmentCompilerPass(
            $GLOBALS['TYPO3_CONF_VARS']['FE']['debug'],
            Environment::getContext()->isTesting()
        )
    );
};
