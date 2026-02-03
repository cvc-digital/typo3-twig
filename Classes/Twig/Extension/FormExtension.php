<?php

declare(strict_types=1);

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

namespace Cvc\Typo3\CvcTwig\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Form\Domain\Exception\RenderingException;
use TYPO3\CMS\Form\Domain\Factory\ArrayFormFactory;
use TYPO3\CMS\Form\Domain\Factory\FormFactoryInterface;
use TYPO3\CMS\Form\Mvc\Persistence\FormPersistenceManagerInterface;

/**
 * @internal
 */
class FormExtension extends AbstractExtension
{
    public function __construct(
        private readonly FormPersistenceManagerInterface $formPersistenceManager,
        private readonly Typo3Version $typo3Version,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('t3_form_render', [$this, 'formRender'], ['is_safe' => ['html'], 'needs_context' => true]),
        ];
    }

    /**
     * Renders a form using the `form framework <https://docs.typo3.org/typo3cms/extensions/form/Index.html>`__.
     *
     * @param array $context Complete context of the twig template
     * @param string|null $persistenceIdentifier The identifier of the form, if a YAML file is used. If :code:`null`, then a Factory class needs to be set.
     * @param string      $factoryClass          the fully qualified class name of the factory
     * @param string|null $prototypeName         name of the prototype to use
     * @param array       $overrideConfiguration Factory specific configuration. This will allow to add additional configuration related to the current view.
     *
     * @throws RenderingException
     */
    public function formRender(
        array $context,
        ?string $persistenceIdentifier = null,
        string $factoryClass = ArrayFormFactory::class,
        ?string $prototypeName = null,
        array $overrideConfiguration = []
    ): string {
        if (!empty($persistenceIdentifier)) {
            if ($this->typo3Version->getMajorVersion() < 14 && $this->typo3Version->getMajorVersion() >= 13) {
                $formConfiguration = $this->formPersistenceManager->load($persistenceIdentifier, [], []);
            } else {
                $formConfiguration = $this->formPersistenceManager->load($persistenceIdentifier);
            }
            ArrayUtility::mergeRecursiveWithOverrule(
                $formConfiguration,
                $overrideConfiguration
            );
            $overrideConfiguration = $formConfiguration;
            $overrideConfiguration['persistenceIdentifier'] = $persistenceIdentifier;
        }

        if (empty($prototypeName)) {
            $prototypeName = $overrideConfiguration['prototypeName'] ?? 'standard';
        }

        /** @var FormFactoryInterface $factory */
        $factory = GeneralUtility::makeInstance($factoryClass);
        $formDefinition = $factory->build($overrideConfiguration, $prototypeName);

        /** @var Request $request */
        $request = $context['request'] ?? null;
        assert($request instanceof Request);
        $form = $formDefinition->bind($request);

        return $form->render();
    }
}
