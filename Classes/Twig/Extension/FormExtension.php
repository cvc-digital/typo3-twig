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

namespace Cvc\Typo3\CvcTwig\Twig\Extension;

use Cvc\Typo3\CvcTwig\Extbase\Mvc\ControllerContextStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Request;
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
    private ObjectManager $objectManager;
    private ControllerContextStack $controllerContextStack;

    public function __construct(ControllerContextStack $controllerContextStack)
    {
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->controllerContextStack = $controllerContextStack;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('t3_form_render', [$this, 'formRender'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Renders a form using the `form framework <https://docs.typo3.org/typo3cms/extensions/form/Index.html>`__.
     *
     * @param string|null $persistenceIdentifier The identifier of the form, if a YAML file is used. If :code:`null`, then a Factory class needs to be set.
     * @param string      $factoryClass          the fully qualified class name of the factory
     * @param string|null $prototypeName         name of the prototype to use
     * @param array       $overrideConfiguration Factory specific configuration. This will allow to add additional configuration related to the current view.
     *
     * @throws \TYPO3\CMS\Form\Domain\Exception\RenderingException
     */
    public function formRender(
        string $persistenceIdentifier = null,
        string $factoryClass = ArrayFormFactory::class,
        string $prototypeName = null,
        array $overrideConfiguration = []
    ): string {
        $controllerContext = $this->controllerContextStack->getControllerContext();

        if (!empty($persistenceIdentifier)) {
            $formPersistenceManager = $this->objectManager->get(FormPersistenceManagerInterface::class);
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
        $factory = $this->objectManager->get($factoryClass);
        $formDefinition = $factory->build($overrideConfiguration, $prototypeName);
        $response = $controllerContext->getResponse() ?? $this->objectManager->get(Response::class);
        assert($response instanceof Response);
        $request = $controllerContext->getRequest();
        assert($request instanceof Request);
        $form = $formDefinition->bind($request, $response);

        // If the controller context does not contain a response object, this viewhelper is used in a
        // fluid template rendered by the FluidTemplateContentObject. Handle the StopActionException
        // as there is no extbase dispatcher involved that catches that. */
        /** @var Response|null $responseFromControllerContext */
        $responseFromControllerContext = $controllerContext->getResponse();
        if ($responseFromControllerContext === null) {
            try {
                return $form->render();
            } catch (\TYPO3\CMS\Extbase\Mvc\Exception\StopActionException $exception) {
                return $response->shutdown();
            }
        }

        return $form->render();
    }
}
