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

namespace Cvc\Typo3\CvcTwig\Twig\Loader;

use Twig\Error\LoaderError;
use Twig\Loader\LoaderInterface;
use Twig\Source;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Loads templates from TYPO3 extensions.
 *
 * This loader support the TYPO3 notation for paths.
 * For example you can load a template like this: EXT:twig/Resources/Private/TwigTemplates/example.html.twig
 */
class Typo3Loader implements LoaderInterface
{
    /**
     * @var array
     */
    private $cache = [];
    /**
     * @var array
     */
    private $errorCache = [];

    /**
     * {@inheritdoc}
     *
     * @throws LoaderError
     */
    public function getSourceContext(string $name): Source
    {
        $path = $this->findTemplate($name);

        return new Source(\file_get_contents($path), $name, $path);
    }

    public function getCacheKey(string $name): string
    {
        return $name;
    }

    /**
     * {@inheritdoc}
     *
     * @throws LoaderError
     */
    public function isFresh(string $name, int $time): bool
    {
        return \filemtime($this->findTemplate($name)) <= $time;
    }

    /**
     * {@inheritdoc}
     *
     * @throws LoaderError
     */
    public function exists(string $name): bool
    {
        if (isset($this->cache[$name])) {
            return true;
        }

        return false !== $this->findTemplate($name, false);
    }

    /**
     * Checks if the template can be found.
     *
     * @param string $name  The template name
     * @param bool   $throw Whether to throw an exception when an error occurs
     *
     * @throws LoaderError
     *
     * @return false|string The template name or false
     */
    private function findTemplate(string $name, bool $throw = true)
    {
        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }
        if (isset($this->errorCache[$name])) {
            if (!$throw) {
                return false;
            }
            throw new LoaderError($this->errorCache[$name]);
        }
        $path = GeneralUtility::getFileAbsFileName($name);
        if (!empty($path) && \is_file($path)) {
            return $this->cache[$name] = $path;
        }
        $this->errorCache[$name] = \sprintf('Unable to find template "%s".', $name);
        if (!$throw) {
            return false;
        }
        throw new LoaderError($this->errorCache[$name]);
    }
}
