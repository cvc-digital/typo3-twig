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

namespace Cvc\Typo3\CvcTwig\Twig\Extension;

use Cvc\Typo3\CvcTwig\Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Response;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Form\Domain\Factory\ArrayFormFactory;
use TYPO3\CMS\Form\Domain\Factory\FormFactoryInterface;
use TYPO3\CMS\Form\Mvc\Persistence\FormPersistenceManagerInterface;

/**
 * @internal
 */
class FormExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('t3_form_render', [static::class, 'formRender'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    /**
     * @param string|null $persistenceIdentifier the persistence identifier for the form
     * @param string|null $factoryClass          the fully qualified class name of the factory
     * @param string|null $prototypeName         name of the prototype to use
     * @param array       $overrideConfiguration factory specific configuration
     *
     * @return string
     */
    public static function formRender(
        Environment $environment,
        string $persistenceIdentifier = null,
        string $factoryClass = ArrayFormFactory::class,
        string $prototypeName = null,
        array $overrideConfiguration = []
    ): string {
        $controllerContext = $environment->getControllerContext();
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);

        if (!empty($persistenceIdentifier)) {
            $formPersistenceManager = $objectManager->get(FormPersistenceManagerInterface::class);
            $formConfiguration = $formPersistenceManager->load($persistenceIdentifier);
            ArrayUtility::mergeRecursiveWithOverrule(
                $formConfiguration,
                $overrideConfiguration
            );
            $overrideConfiguration = $formConfiguration;
            $overrideConfiguration['persistenceIdentifier'] = $persistenceIdentifier;
        }

        if (empty($prototypeName)) {
            $prototypeName = isset($overrideConfiguration['prototypeName']) ? $overrideConfiguration['prototypeName'] : 'standard';
        }

        /** @var FormFactoryInterface $factory */
        $factory = $objectManager->get($factoryClass);
        $formDefinition = $factory->build($overrideConfiguration, $prototypeName);
        $response = $controllerContext->getResponse() ?? $objectManager->get(Response::class);
        $form = $formDefinition->bind($controllerContext->getRequest(), $response);

        // If the controller context does not contain a response object, this viewhelper is used in a
        // fluid template rendered by the FluidTemplateContentObject. Handle the StopActionException
        // as there is no extbase dispatcher involved that catches that. */
        if ($controllerContext->getResponse() === null) {
            try {
                return $form->render();
            } catch (\TYPO3\CMS\Extbase\Mvc\Exception\StopActionException $exception) {
                return $response->shutdown();
            }
        }

        return $form->render();
    }
}
