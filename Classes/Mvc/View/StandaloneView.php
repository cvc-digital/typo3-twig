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

namespace Cvc\Typo3\CvcTwig\Mvc\View;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\ChainLoader;
use Twig\Loader\FilesystemLoader;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * A standalone view.
 * Should be used as view if you want to use Twig without Extbase.
 *
 * In contrast to the Fluid standalone view this class has no dependency to Extbase.
 */
final class StandaloneView
{
    /**
     * @var string
     */
    private $templateName;

    /**
     * @var string[]
     */
    private $templateRootPaths = [];

    /**
     * @var array[]
     */
    private $namespaces = [];

    /**
     * @var array
     *
     * @see assign()
     */
    private $variables;

    /**
     * @var Environment
     */
    private $environment;

    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * Renders the view.
     *
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     *
     * @return string The rendered view
     */
    public function render()
    {
        $templatePaths = array_map([GeneralUtility::class, 'getFileAbsFileName'], $this->templateRootPaths);

        // reverse order of template paths since twig will match first path first
        // to match the fluid behavior and to make it easier to extend, we want to match last path first
        $templatePaths = array_reverse($templatePaths, true);

        $fileSystemLoader = new FilesystemLoader($templatePaths);
        foreach ($this->namespaces as $namespace => $namespacedPaths) {
            $namespacedPaths = array_reverse($namespacedPaths);
            $namespacedPaths = array_map([GeneralUtility::class, 'getFileAbsFileName'], $namespacedPaths);
            $fileSystemLoader->setPaths($namespacedPaths, $namespace);
        }

        $originalLoader = $this->environment->getLoader();
        $this->environment->setLoader(new ChainLoader([$fileSystemLoader, $originalLoader]));

        $content = $this->environment->render($this->templateName, $this->variables);

        $this->environment->setLoader($originalLoader);

        return $content;
    }

    /**
     * Add a variable to $this->viewData.
     * Can be chained, so $this->view->assign(..., ...)->assign(..., ...); is possible.
     *
     * @param string $key   Key of variable
     * @param mixed  $value Value of object
     *
     * @return $this
     */
    public function assign($key, $value)
    {
        $this->variables[$key] = $value;

        return $this;
    }

    /**
     * Add multiple variables to $this->viewData.
     *
     * @param array $values array in the format array(key1 => value1, key2 => value2)
     *
     * @return $this
     */
    public function assignMultiple(array $values)
    {
        foreach ($values as $key => $value) {
            $this->assign($key, $value);
        }

        return $this;
    }

    /**
     * Set the template name.
     */
    public function setTemplateName(string $templateName)
    {
        $this->templateName = $templateName;
    }

    /**
     * Sets the template root paths.
     *
     * @param string[] $templateRootPaths
     */
    public function setTemplateRootPaths(array $templateRootPaths)
    {
        $this->templateRootPaths = $templateRootPaths;
    }

    /**
     * Sets the Twig namespaces.
     */
    public function setNamespaces(array $namespaces): void
    {
        $this->namespaces = $namespaces;
    }
}
