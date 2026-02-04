<?php

declare(strict_types=1);

/*
 * Twig extension for TYPO3 CMS
 * Copyright (C) 2026 CARL von CHIARI GmbH
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

namespace Cvc\Typo3\CvcTwig\ContentObject;

use Cvc\Typo3\CvcTwig\View\TwigViewAdapter;
use Cvc\Typo3\CvcTwig\View\TwigViewFactory;
use http\Exception\RuntimeException;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\Web\RequestBuilder;
use TYPO3\CMS\Frontend\ContentObject\AbstractContentObject;
use TYPO3\CMS\Frontend\ContentObject\ContentDataProcessor;

/**
 * This class provides the TWIGTEMPLATE Content Object.
 *
 * It can be used in a similar way to FLUIDTEMPLATE:
 *
 * page.10 = TWIGTEMPLATE
 * page.10 {
 *     templateName = example.html.twig
 *     variables {
 *         foo = TEXT
 *         foo.value = Bar!
 *     }
 *     templateRootPaths {
 *         10 = EXT:twig/Resources/Private/TwigTemplates/
 *     }
 * }
 *
 * @internal
 */
class TwigTemplateContentObject extends AbstractContentObject
{
    private ContentDataProcessor $contentDataProcessor;
    private TypoScriptService $typoScriptService;

    private TwigViewFactory $twigViewFactory;

    public function __construct(ContentDataProcessor $contentDataProcessor, TypoScriptService $typoScriptService, TwigViewFactory $twigViewFactory)
    {
        $this->contentDataProcessor = $contentDataProcessor;
        $this->typoScriptService = $typoScriptService;
        $this->twigViewFactory = $twigViewFactory;
    }

    /**
     * Rendering the cObject, TWIGTEMPLATE.
     *
     * Configuration properties:
     * - templateName string+stdWrap The Twig template name.
     * - templateRootPaths array of filepath+stdWrap Root paths to the templates.
     * - variables array of cObjects, the keys are the variable names that can be used in the template.
     * - dataProcessing array of data processors which are classes to manipulate $data
     *
     * Example:
     * 10 = TWIGTEMPLATE
     * 10 {
     *     templateName = example.html.twig
     *     variables {
     *         foo = TEXT
     *         foo.value = Bar!
     *     }
     *     templateRootPaths {
     *         10 = EXT:twig/Resources/Private/TwigTemplates
     *     }
     *     namespaces {
     *         components {
     *             10 = EXT:example_site/Private/frontend/src/components
     *         }
     *     }
     * }
     *
     * @param array<mixed> $conf Array of TypoScript properties
     *
     * @return string The HTML output
     */
    public function render($conf = []): string
    {
        if (is_null($this->cObj)) {
            throw new RuntimeException('ContentObjectRenderer is required.');
        }

        $templateName = isset($conf['templateName.'])
            ? $this->cObj->stdWrap($conf['templateName'] ?? '', $conf['templateName.'])
            : $conf['templateName'];

        $templateRootPaths = isset($conf['templateRootPaths.'])
            ? $this->applyStandardWrapToTwigPaths($conf['templateRootPaths.'])
            : [];

        $namespaces = [];
        if (isset($conf['namespaces.'])) {
            foreach ($conf['namespaces.'] as $namespace => $paths) {
                $namespaces[rtrim($namespace, '.')] = $paths;
            }
        }
        $variables = $this->getContentObjectVariables($conf);
        $variables = $this->contentDataProcessor->process($this->cObj, $conf, $variables);

        $viewFactoryData = new ViewFactoryData(
            templateRootPaths: $templateRootPaths,
        );
        $view = $this->twigViewFactory->create($viewFactoryData);

        $this->setExtbaseVariables($conf, $view);

        $settings = $this->getSettings($conf);
        if ($settings) {
            $view->assign('settings', $settings);
        }

        $view->setTemplateRootPaths($templateRootPaths);
        $view->setNamespaces($namespaces);
        $view->assignMultiple($variables);
        $view->setRequest($this->request);

        return $view->render($templateName);
    }

    /**
     * Applies stdWrap on Twig path definitions.
     *
     * @param array<int|string, string> $paths
     *
     * @return array<string, string|null>
     */
    private function applyStandardWrapToTwigPaths(array $paths): array
    {
        if (is_null($this->cObj)) {
            throw new RuntimeException('ContentObjectRenderer is required.');
        }

        $finalPaths = [];
        foreach ($paths as $key => $path) {
            $key = (string) $key;
            if (str_ends_with($key, '.')) {
                if (isset($paths[\mb_substr($key, 0, -1)])) {
                    continue;
                }
                $path = $this->cObj->stdWrap('', $path);
            } elseif (isset($paths[$key.'.'])) {
                $path = $this->cObj->stdWrap($path, $paths[$key.'.']);
            }
            $finalPaths[$key] = $path;
        }

        return $finalPaths;
    }

    /**
     * Compile rendered content objects in variables array ready to assign to the view.
     *
     * @param array<mixed> $conf Configuration array
     *
     * @throws \InvalidArgumentException
     *
     * @return array<mixed> the variables to be assigned
     */
    private function getContentObjectVariables(array $conf): array
    {
        if (is_null($this->cObj)) {
            throw new RuntimeException('ContentObjectRenderer is required.');
        }

        $variables = [];
        $reservedVariables = ['data', 'current'];
        // Accumulate the variables to be process and loop them through cObjGetSingle
        $variablesToProcess = array_key_exists('variables.', $conf) ? (array) $conf['variables.'] : null;
        if (is_iterable($variablesToProcess)) {
            foreach ($variablesToProcess as $variableName => $cObjType) {
                if (\is_array($cObjType)) {
                    continue;
                }
                if (!\in_array($variableName, $reservedVariables)) {
                    $variables[$variableName] = $this->cObj->cObjGetSingle($cObjType, $variablesToProcess[$variableName.'.']);
                } else {
                    throw new \InvalidArgumentException('Cannot use reserved name "'.$variableName.'" as variable name in TWIGTEMPLATE.', 1288095720);
                }
            }
        }
        $variables['data'] = $this->cObj->data;
        $variables['current'] = array_key_exists($this->cObj->currentValKey, $this->cObj->data) ? $this->cObj->data[$this->cObj->currentValKey] : null;

        return $variables;
    }

    /**
     * Returns any TypoScript settings.
     *
     * @param array<mixed> $conf
     *
     * @return array<mixed>|null
     */
    private function getSettings(array $conf): ?array
    {
        if (isset($conf['settings.'])) {
            return $this->typoScriptService->convertTypoScriptArrayToPlainArray($conf['settings.']);
        }

        return null;
    }

    /**
     * Set some extbase variables if given.
     *
     * @param array<mixed> $conf Configuration array
     *
     * @see \TYPO3\CMS\Frontend\ContentObject\ContentContentObject
     */
    private function setExtbaseVariables(array $conf, TwigViewAdapter $view): void
    {
        if (is_null($this->cObj)) {
            throw new RuntimeException('ContentObjectRenderer is required.');
        }

        // @todo: It is currently unclear if the if's below can happen at all: An extbase request has been
        //        prepared, but the setup of plugin name, controller extension name and friends
        //        did not happen? Maybe these four if's are useless and the main if that
        //        tests for all four properties is fine? Maybe the main if below is obsolete, too?
        //        This comment was added when StandaloneView still had a default constructor that actively
        //        creates a request by default. It might be more possible to resolve this when this is gone.
        $request = $this->request;
        $requestPluginName = (string) $this->cObj->stdWrapValue('pluginName', $conf['extbase.'] ?? []);
        if ($requestPluginName && $request instanceof RequestInterface) {
            $request = $request->withPluginName($requestPluginName);
            $view->setRequest($request);
        }
        $requestControllerExtensionName = (string) $this->cObj->stdWrapValue('controllerExtensionName', $conf['extbase.'] ?? []);
        if ($requestControllerExtensionName && $request instanceof RequestInterface) {
            $request = $request->withControllerExtensionName($requestControllerExtensionName);
            $view->setRequest($request);
        }
        $requestControllerName = (string) $this->cObj->stdWrapValue('controllerName', $conf['extbase.'] ?? []);
        if ($requestControllerName && $request instanceof RequestInterface) {
            $request = $request->withControllerName($requestControllerName);
            $view->setRequest($request);
        }
        $requestControllerActionName = (string) $this->cObj->stdWrapValue('controllerActionName', $conf['extbase.'] ?? []);
        if ($requestControllerActionName && $request instanceof RequestInterface) {
            $request = $request->withControllerActionName($requestControllerActionName);
            $view->setRequest($request);
        }

        if ($requestPluginName && $requestControllerExtensionName && $requestControllerName && $requestControllerActionName) {
            // @todo: Yep, ugly. Having all four properties indicates an extbase plugin and then starts
            //        extbase configuration manager. See https://forge.typo3.org/issues/78842 and investigate
            //        if we still need this?
            $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);
            $configurationManager->setConfiguration([
                'extensionName' => $requestControllerExtensionName,
                'pluginName' => $requestPluginName,
            ]);
            if (!isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions'][$requestControllerExtensionName]['plugins'][$requestPluginName]['controllers'])) {
                $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions'][$requestControllerExtensionName]['plugins'][$requestPluginName]['controllers'] = [
                    $requestControllerName => [
                        'actions' => [
                            $requestControllerActionName,
                        ],
                    ],
                ];
            }
            $requestBuilder = GeneralUtility::makeInstance(RequestBuilder::class);
            $this->request = $requestBuilder->build($this->request);
        }
    }
}
