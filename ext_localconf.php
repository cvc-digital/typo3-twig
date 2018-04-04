<?php

// register template content object for TWIGTEMPLATE
$GLOBALS['TYPO3_CONF_VARS']['FE']['ContentObjects'] = \array_merge($GLOBALS['TYPO3_CONF_VARS']['FE']['ContentObjects'], [
    'TWIGTEMPLATE' => \Carl\Typo3\Twig\ContentObject\TwigTemplateContentObject::class,
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
