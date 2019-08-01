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

namespace Cvc\Typo3\CvcTwig\Tests\Functional\Twig\Extension;

use Cvc\Typo3\CvcTwig\Twig\Test\FunctionalTestCase;
use Twig\Environment;
use Twig\Extension\ExtensionInterface;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class IntegrationTest extends FunctionalTestCase
{
    protected $testExtensionsToLoad = [
        'typo3conf/ext/cvc_twig',
    ];

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        Bootstrap::initializeLanguageObject();
    }

    protected function getFixturesDir()
    {
        return __DIR__.'/Fixtures';
    }

    protected function getExtensions()
    {
        $environment = GeneralUtility::getContainer()->get(Environment::class);
        $extensions = array_filter($environment->getExtensions(), function (ExtensionInterface $extension): bool {
            return mb_strpos(get_class($extension), 'Twig\Extension') !== 0;
        });

        return $extensions;
    }
}
