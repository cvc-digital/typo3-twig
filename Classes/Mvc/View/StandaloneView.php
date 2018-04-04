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

namespace Carl\Typo3\Twig\Mvc\View;

use Carl\Typo3\Twig\Twig\Cache\Typo3Cache;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * A standalone view.
 * Should be used as view if you want to use Twig without Extbase.
 *
 * In contrast to the Fluid standalone view this class has no dependency to Extbase.
 */
class StandaloneView
{
    /**
     * @var string
     */
    protected $templateName;

    /**
     * @var string[]
     */
    protected $templateRootPaths;

    /**
     * @var array
     *
     * @see assign()
     */
    protected $variables;

    /**
     * Renders the view.
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     *
     * @return string The rendered view
     */
    public function render()
    {
        $templatePaths = array_map(function (string $path) {
            return GeneralUtility::getFileAbsFileName($path);
        }, $this->templateRootPaths);

        return (new Environment(
            new FilesystemLoader($templatePaths),
            [
                'debug' => $GLOBALS['TYPO3_CONF_VARS']['FE']['debug'],
                'cache' => GeneralUtility::makeInstance(Typo3Cache::class),
            ]
        ))->render($this->templateName, $this->variables);
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
     *
     * @param string $templateName
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
}
