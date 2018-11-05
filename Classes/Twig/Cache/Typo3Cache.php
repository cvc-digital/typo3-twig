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

namespace Cvc\Typo3\CvcTwig\Twig\Cache;

use Twig\Cache\CacheInterface;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Typo3Cache implements CacheInterface
{
    /**
     * @var PhpFrontend
     */
    private $phpFrontend;

    /**
     * @var FrontendInterface
     */
    private $timestampCache;

    public function __construct()
    {
        $cacheManager = GeneralUtility::makeInstance(CacheManager::class);

        $phpFrontend = $cacheManager->getCache('twig_templates');
        if (!$phpFrontend instanceof PhpFrontend) {
            throw new \RuntimeException('Cannot use '.get_class($phpFrontend).' to cache twig templates.');
        }

        $this->phpFrontend = $phpFrontend;
        $this->timestampCache = $cacheManager->getCache('twig_timestamps');
    }

    public function generateKey($name, $className)
    {
        return \hash('sha256', $className);
    }

    public function write($key, $content)
    {
        if (mb_strpos($content, '<?php') === 0) {
            $content = mb_substr($content, 5);
        }

        $this->phpFrontend->set($key, $content);
        $this->timestampCache->set($key, $GLOBALS['EXEC_TIME']);
    }

    public function load($key)
    {
        $this->phpFrontend->requireOnce($key);
    }

    public function getTimestamp($key)
    {
        return (int) $this->timestampCache->get($key);
    }
}
