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

namespace Cvc\Typo3\CvcTwig\Tests\Functional\ContentObject;

use TYPO3\CMS\Core\Tests\Functional\SiteHandling\SiteBasedTestTrait;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class RenderTwigTemplateTest extends FunctionalTestCase
{
    use SiteBasedTestTrait;

    protected array $testExtensionsToLoad = [
        'typo3conf/ext/cvc_twig',
        'typo3conf/ext/cvc_twig/Tests/Functional/Fixtures/Extensions/twig_test',
    ];

    private const ROOT_PAGE_ID = 1;

    protected function setUp(): void
    {
        parent::setUp();
        $this->importCSVDataSet(__DIR__.'/../../../.Build/vendor/typo3/cms-core/Tests/Functional/Fixtures/pages.csv');
        $this->writeSiteConfiguration(
            'frontend_twig_contentobject_test',
            $this->buildSiteConfiguration(self::ROOT_PAGE_ID, '/'),
        );
    }

    public function testTemplateIsRendered(): void
    {
        $this->setUpFrontendRootPage(
            self::ROOT_PAGE_ID,
            [
                'EXT:cvc_twig/Tests/Functional/Fixtures/Extensions/twig_test/Configuration/TypoScript/page.typoscript'
            ]
        );
        $response = $this->executeFrontendSubRequest((new InternalRequest())->withPageId(1));

        $this->assertStringContainsString("Foo Bar! Settings: Ipsum.\n", (string) $response->getBody());
    }
}
