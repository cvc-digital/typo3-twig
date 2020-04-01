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

namespace Cvc\Typo3\CvcTwig\ContentObject;

use Cvc\Typo3\CvcTwig\Mvc\View\StandaloneViewFactory;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;
use TYPO3\CMS\Frontend\ContentObject\AbstractContentObject;
use TYPO3\CMS\Frontend\ContentObject\ContentDataProcessor;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * This class provides the TWIGTEMPLATE Content Object.
 *
 * It can be used in a similar way to FLUIDTEMPLATE:
 *
 * page.10 = TWIGTEMPLATE
 * page.10 {
 *     template = example.html.twig
 *     variables {
 *         foo = TEXT
 *         foo.value = Bar!
 *     }
 *     templateRootPaths {
 *         10 = EXT:twig/Resources/Private/TwigTemplates
 *     }
 * }
 *
 * @internal
 */
class TwigTemplateContentObject extends AbstractContentObject
{
    private ContentDataProcessor $contentDataProcessor;
    private StandaloneViewFactory $standaloneViewFactory;

    public function __construct(ContentObjectRenderer $cObj)
    {
        parent::__construct($cObj);
        // fixme: checkout how to use dependency injection
        $contentDataProcessor = GeneralUtility::makeInstance(ContentDataProcessor::class);
        assert($contentDataProcessor instanceof ContentDataProcessor);
        $standaloneViewFactory = GeneralUtility::getContainer()->get(StandaloneViewFactory::class);
        assert($standaloneViewFactory instanceof StandaloneViewFactory);

        $this->contentDataProcessor = $contentDataProcessor;
        $this->standaloneViewFactory = $standaloneViewFactory;
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
     * @param array $conf Array of TypoScript properties
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     *
     * @return string The HTML output
     */
    public function render($conf = [])
    {
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

        $view = $this->standaloneViewFactory->create();
        $view->setTemplateName($templateName);
        $view->setTemplateRootPaths($templateRootPaths);
        $view->setNamespaces($namespaces);
        $view->assignMultiple($variables);

        return $view->render();
    }

    /**
     * Applies stdWrap on Twig path definitions.
     *
     * @return array
     */
    private function applyStandardWrapToTwigPaths(array $paths)
    {
        $finalPaths = [];
        foreach ($paths as $key => $path) {
            if (StringUtility::endsWith($key, '.')) {
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
     * @param array $conf Configuration array
     *
     * @throws \InvalidArgumentException
     *
     * @return array the variables to be assigned
     */
    private function getContentObjectVariables(array $conf)
    {
        $variables = [];
        $reservedVariables = ['data', 'current'];
        // Accumulate the variables to be process and loop them through cObjGetSingle
        $variablesToProcess = (array) $conf['variables.'];
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
        $variables['data'] = $this->cObj->data;
        $variables['current'] = $this->cObj->data[$this->cObj->currentValKey];

        return $variables;
    }
}
