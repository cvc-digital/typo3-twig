<?php

/*
 * Twig extension for TYPO3 CMS
 * Copyright (C) 2023 CARL von CHIARI GmbH
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

namespace Cvc\Typo3\CvcTwig\Tests\Functional\Mvc\View;

use Cvc\Typo3\CvcTwig\View\TwigViewAdapter;
use Cvc\Typo3\CvcTwig\View\TwigViewFactory;
use PHPUnit\Framework\Attributes\DataProvider;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3Fluid\Fluid\View\TemplateAwareViewInterface as FluidStandaloneTemplateAwareViewInterface;
use TYPO3Fluid\Fluid\View\ViewInterface as FluidStandaloneViewInterface;

class StandaloneViewTest extends FunctionalTestCase
{
    protected array $coreExtensionsToLoad = [
        'form',
    ];

    protected array $testExtensionsToLoad = [
        'typo3conf/ext/cvc_twig',
        'typo3conf/ext/cvc_twig/Tests/Functional/Fixtures/Extensions/twig_test',
    ];

    public static function renderCastsToStringDataProvider(): iterable
    {
        return [
            ['<h1>Hello I am an example!</h1>
<p>
    The variable <code>foo</code> has the value "bar".
</p>', '<h1>Hello I am an example!</h1>
<p>
    The variable <code>foo</code> has the value "bar".
</p>'],
            [123, '123'],
            [123.456, '123.456'],
            [
                new class () {
                    public function __toString(): string
                    {
                        return 'Stringable';
                    }
                },
                'Stringable',
            ],
        ];
    }

    #[DataProvider('renderCastsToStringDataProvider')]
    public function testTwigViewRendersTemplate(mixed $viewReturnValue, string $expectedResult)
    {
        $view = new class ($viewReturnValue) implements FluidStandaloneViewInterface, FluidStandaloneTemplateAwareViewInterface {
            public function __construct(private mixed $viewReturnValue) {}
            public function render(string $templateFileName = '')
            {
                return $this->viewReturnValue;
            }
            public function assign($name, $value)
            {
                return $this;
            }
            public function assignMultiple($variables)
            {
                return $this;
            }
            public function renderPartial($partialName, $sectionName, array $variables, $ignoreUnknown = false)
            {
                return '';
            }
            public function renderSection($sectionName, array $variables = [], $ignoreUnknown = false)
            {
                return '';
            }
        };

        $subject = new TwigViewAdapter($view);
        self::assertSame($expectedResult, $subject->render());
    }
}
