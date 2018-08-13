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

// register template content object for TWIGTEMPLATE
$GLOBALS['TYPO3_CONF_VARS']['FE']['ContentObjects'] = \array_merge($GLOBALS['TYPO3_CONF_VARS']['FE']['ContentObjects'], [
    'TWIGTEMPLATE' => \Cvc\Typo3\Twig\ContentObject\TwigTemplateContentObject::class,
]);

// register caches for twig
if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['twig_templates'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['twig_templates'] = [
        'backend' => \TYPO3\CMS\Core\Cache\Backend\FileBackend::class,
        'frontend' => \TYPO3\CMS\Core\Cache\Frontend\PhpFrontend::class,
    ];
}
if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['twig_timestamps'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['twig_timestamps'] = [];
}
