<?php

/*
 * Twig extension for TYPO3 CMS
 * Copyright (C) 2019 CARL von CHIARI GmbH
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

namespace Cvc\Typo3\CvcTwig\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Twig\Environment;

/**
 * @internal
 */
final class TwigEnvironmentCompilerPass implements CompilerPassInterface
{
    /**
     * Indicates that debugging context is enabled.
     *
     * @var bool
     */
    private $debug;

    /**
     * Indicates that test context is enabled.
     *
     * @var bool
     */
    private $test;

    public function __construct(bool $debug, bool $test)
    {
        $this->debug = $debug;
        $this->test = $test;
    }

    public function process(ContainerBuilder $container)
    {
        $twigEnvironmentDefinition = $container->getDefinition(Environment::class);

        $serviceIds = array_keys($container->findTaggedServiceIds('twig.extension'));
        foreach ($serviceIds as $serviceId) {
            $twigEnvironmentDefinition->addMethodCall('addExtension', [$container->getDefinition($serviceId)]);
        }

        if ($this->test) {
            // during integration tests, the environment needs to be retrieved directly from the container
            $twigEnvironmentDefinition->setPublic(true);
        }

        $container->setParameter('twig.debug', $this->debug);
    }
}
