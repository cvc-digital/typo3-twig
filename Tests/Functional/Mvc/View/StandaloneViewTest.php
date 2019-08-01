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

namespace Cvc\Typo3\CvcTwig\Tests\Functional\Mvc\View;

use Cvc\Typo3\CvcTwig\Mvc\View\StandaloneViewFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class StandaloneViewTest extends FunctionalTestCase
{
    protected $testExtensionsToLoad = [
        'typo3conf/ext/cvc_twig',
    ];

    public function test_twig_view_renders_template()
    {
        $standaloneViewFactory = GeneralUtility::getContainer()->get(StandaloneViewFactory::class);
        $twigView = $standaloneViewFactory->create();

        $twigView->assign('foo', 'bar');
        $twigView->setTemplateRootPaths(['EXT:cvc_twig/Resources/Private/TwigTemplates/']);
        $twigView->setTemplateName('example.html.twig');

        $renderedView = $twigView->render();
        $expectedOutput = <<<'HTML'
<h1>Hello I am an example!</h1>
<p>
    The variable <code>foo</code> has the value "bar".
</p>

HTML;

        $this->assertSame($expectedOutput, $renderedView);
    }
}
