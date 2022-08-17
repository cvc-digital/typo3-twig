<?php

/*
 * Twig extension for TYPO3 CMS
 * Copyright (C) 2022 CARL von CHIARI GmbH
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
use Twig\TwigFilter;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Service\TypoLinkCodecService;

class TypoLinkExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('t3_typolink', [static::class, 'filterTypoLink'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Creates a link from fields supported by the link wizard.
     * The :code:`linkText` will be wrapped within a link tag.
     *
     * @param string $linkText              the text that should be wrapped in an <a>-tag
     * @param string $parameter             :code:`stdWrap.typolink` style parameter string.
     * @param string $target                Specifies where to open the linked document (e.g. :code:`_blank`).
     * @param string $class                 class added to the :code:`<a>`-tag
     * @param string $title                 Title attribute of the :code:`<a>`-tag. It can give more information to the user in form of a tooltip.
     * @param string $additionalParams      This is parameters that are added to the end of the URL. This must be code ready to insert after the last parameter.
     *                                      See: `TypoLink documentation <https://docs.typo3.org/typo3cms/TyposcriptReference/Functions/Typolink.html#additionalparams>`__
     * @param array  $additionalAttributes  additional HTML attributes that are added to the :code:`<a>`-tag
     * @param bool   $useCacheHash          If set, the additionalParams list is exploded and calculated into a hash string appended to the URL, like "&cHash=ae83fd7s87".
     *                                      See: `TypoLink documentation <https://docs.typo3.org/typo3cms/TyposcriptReference/Functions/Typolink.html#usecachehash>`__
     * @param bool   $addQueryString        Adds the query string to start of the link.
     *                                      See: `TypoLink documentation <https://docs.typo3.org/typo3cms/TyposcriptReference/Functions/Typolink.html#addquerystring>`__
     * @param string $addQueryStringMethod  If set to GET or POST, then the parsed query arguments (GET or POST data) will be used.
     *                                      See: `TypoLink documentation <https://docs.typo3.org/typo3cms/TyposcriptReference/Functions/Typolink.html#addquerystring>`__
     * @param string $addQueryStringExclude List of query arguments to exclude from the link. Typical examples are :code:`L` or :code:`cHash`.
     *                                      See: `TypoLink documentation <https://docs.typo3.org/typo3cms/TyposcriptReference/Functions/Typolink.html#addquerystring>`__
     * @param bool   $absolute              Forces links to internal pages to be absolute, thus having a proper URL scheme and domain prepended.
     *                                      See: `TypoLink documentation <https://docs.typo3.org/typo3cms/TyposcriptReference/Functions/Typolink.html#forceabsoluteurl>`__
     *
     * @example
     *  {# Create links from simple text. #}
     *  {{ 'read_more' | t3_trans | t3_typolink('t3://record?identifier=123') }}
     * @example
     *  {# Create links around complex HTML structures. #}
     *  {% filter t3_typolink('t3://record?identifier=123', target='_blank') %}
     *     <img src="read_more.png alt="read more"/>
     *  {% endfilter %}
     */
    public static function filterTypoLink(
        string $linkText,
        string $parameter,
        string $target = '',
        string $class = '',
        string $title = '',
        string $additionalParams = '',
        array $additionalAttributes = [],
        bool $useCacheHash = false,
        bool $addQueryString = false,
        string $addQueryStringMethod = 'GET',
        string $addQueryStringExclude = '',
        bool $absolute = false
    ): string {
        // Merge the $parameter with other arguments
        $typoLinkParameter = self::createTypoLinkParameterArrayFromArguments(
            $parameter,
            $target,
            $class,
            $title,
            $additionalParams
        );

        // array(param1 -> value1, param2 -> value2) --> param1="value1" param2="value2" for typolink.ATagParams
        $extraAttributes = [];
        foreach ($additionalAttributes as $attributeName => $attributeValue) {
            $extraAttributes[] = $attributeName.'="'.htmlspecialchars($attributeValue).'"';
        }
        $aTagParams = implode(' ', $extraAttributes);

        /** @var ContentObjectRenderer $contentObject */
        $contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $contentObject->start([], '');

        return $contentObject->stdWrap(
            $linkText,
            [
                'typolink.' => [
                    'parameter' => $typoLinkParameter,
                    'ATagParams' => $aTagParams,
                    'useCacheHash' => $useCacheHash,
                    'addQueryString' => $addQueryString,
                    'addQueryString.' => [
                        'method' => $addQueryStringMethod,
                        'exclude' => $addQueryStringExclude,
                    ],
                    'forceAbsoluteUrl' => $absolute,
                ],
            ]
        );
    }

    /**
     * Transforms filter arguments to typo3link.parameters.typoscript option as array.
     *
     * @param string $parameter Example: 19 _blank - "testtitle \"with whitespace\"" &X=y
     *
     * @return string The final TypoLink string
     */
    private static function createTypoLinkParameterArrayFromArguments(
        string $parameter,
        string $target = '',
        string $class = '',
        string $title = '',
        string $additionalParams = ''): string
    {
        $typoLinkCodec = GeneralUtility::makeInstance(TypoLinkCodecService::class);
        $typoLinkConfiguration = $typoLinkCodec->decode($parameter);
        if (empty($typoLinkConfiguration)) {
            return '';
        }

        // Override target if given in target argument
        if ($target) {
            $typoLinkConfiguration['target'] = $target;
        }

        // Combine classes if given in both "parameter" string and "class" argument
        if ($class) {
            $classes = explode(' ', trim($typoLinkConfiguration['class']).' '.trim($class));
            $typoLinkConfiguration['class'] = implode(' ', array_unique(array_filter($classes)));
        }

        // Override title if given in title argument
        if ($title) {
            $typoLinkConfiguration['title'] = $title;
        }

        // Combine additionalParams
        if ($additionalParams) {
            $typoLinkConfiguration['additionalParams'] .= $additionalParams;
        }

        return $typoLinkCodec->encode($typoLinkConfiguration);
    }
}
