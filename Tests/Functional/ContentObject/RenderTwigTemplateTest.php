<?php

/*
 * Twig extension for TYPO3 CMS
 * Copyright (C) 2021 CARL von CHIARI GmbH
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

namespace Cvc\Typo3\CvcTwig\Tests\Functional\ContentObject;

use Nimut\TestingFramework\TestCase\FunctionalTestCase;

class RenderTwigTemplateTest extends FunctionalTestCase
{
    protected $testExtensionsToLoad = [
        'typo3conf/ext/cvc_twig',
        'typo3conf/ext/cvc_twig/Tests/Functional/Fixtures/Extensions/twig_test',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->importDataSet(__DIR__.'/../../../.Build/vendor/nimut/testing-framework/res/Fixtures/Database/pages.xml');
        $this->setUpFrontendRootPage(1, [__DIR__.'/../Fixtures/Extensions/twig_test/Configuration/TypoScript/page.typoscript']);
    }

    public function testTemplateIsRendered(): void
    {
        $this->assertStringContainsString("Foo Bar! Settings: Ipsum.\n", $this->getFrontendResponse(1)->getContent());
    }
}
